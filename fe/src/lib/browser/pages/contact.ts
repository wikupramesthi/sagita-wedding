import { $ } from "../lib/dom";

async function postJSON(url: string, body: unknown) {
	const res = await fetch(url, {
		method: "POST",
		headers: { "Content-Type": "application/json", Accept: "application/json" },
		body: JSON.stringify(body),
	});
	const data = await res.json().catch(() => ({}));
	if (!res.ok)
		throw new Error(data.message || "Failed to send. Please try again.");
	return data;
}

(() => {
	const form = $("contactForm") as HTMLFormElement | null;
	if (!form) return;
	const button = form.querySelector("button") as HTMLButtonElement | null;
	const message = $("contactMsg");
	const idleLabel = button ? button.textContent : "";
	const captchaCode = $("captchaCode");
	const captchaStatus = $("captchaStatus");
	const captchaId = $("captchaId") as HTMLInputElement | null;
	const captchaAnswer = $("captchaAnswer") as HTMLInputElement | null;
	const english = document.documentElement.lang === "en";
	let captchaIdReady = false;

	const refreshCaptcha = async () => {
		captchaIdReady = false;
		if (button) button.disabled = true;
		try {
			const res = await fetch(
				`/api/contact/captcha?lang=${english ? "en" : "id"}`,
				{
					headers: { Accept: "application/json" },
				},
			);
			const data = (await res.json()) as {
				success?: boolean;
				captcha_id?: string;
				image?: string;
				question?: string;
				message?: string;
			};
			if (!res.ok || !data.success || !data.captcha_id || !data.image) {
				throw new Error(data.message || "Verification is unavailable.");
			}
			if (captchaId) captchaId.value = data.captcha_id;
			if (captchaCode) captchaCode.setAttribute("src", data.image);
			if (captchaAnswer) captchaAnswer.value = "";
			captchaIdReady = true;
			if (captchaStatus) {
				captchaStatus.textContent = data.question || "";
			}
		} catch (error) {
			if (captchaStatus) {
				captchaStatus.textContent =
					error instanceof Error
						? error.message
						: english
							? "Verification could not be loaded."
							: "Verifikasi tidak dapat dimuat.";
			}
		}
	};

	form.addEventListener("submit", async (e) => {
		e.preventDefault();
		if (!captchaIdReady) {
			if (captchaStatus) {
				captchaStatus.textContent = english
					? "Please complete the verification."
					: "Silakan isi verifikasi.";
			}
			return;
		}
		if (button) {
			button.disabled = true;
			button.textContent = form.dataset.sending || "Sending...";
		}
		try {
			const payload = Object.fromEntries(new FormData(form).entries());
			const result = await postJSON("/api/contact", payload);
			form.reset();
			if (message) {
				message.className = "text-sm p-3 rounded-xl bg-green-50 text-green-700";
				message.textContent = result.message;
			}
		} catch (err) {
			if (message) {
				message.className = "text-sm p-3 rounded-xl bg-red-50 text-red-700";
				message.textContent = (err as Error).message;
			}
		} finally {
			await refreshCaptcha();
			if (button) {
				button.disabled = !captchaIdReady;
				button.textContent = idleLabel;
			}
		}
	});

	if (button) button.disabled = true;
	void refreshCaptcha();
})();

document.querySelectorAll("[data-faq]").forEach((btn) => {
	btn.addEventListener("click", () => {
		const content = btn.nextElementSibling;
		if (!content) return;
		const willOpen = content.classList.contains("hidden");
		document.querySelectorAll("[data-faq] + div").forEach((d) => {
			d.classList.add("hidden");
		});
		if (willOpen) content.classList.remove("hidden");
	});
});
