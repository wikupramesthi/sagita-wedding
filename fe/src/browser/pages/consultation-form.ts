const form = document.querySelector<HTMLFormElement>("#consultationForm");
const submitButton = document.querySelector<HTMLButtonElement>(
	"#consultationSubmit",
);
const captchaId = document.querySelector<HTMLInputElement>(
	"#consultationCaptchaId",
);
const captchaQuestion = document.getElementById("consultationCaptchaQuestion");
const captchaCode = document.getElementById("consultationCaptchaCode");
const captchaAnswer = document.querySelector<HTMLInputElement>(
	"#consultationCaptchaAnswer",
);
const captchaReload = document.querySelector<HTMLButtonElement>(
	"#consultationCaptchaReload",
);
const message = document.getElementById("consultationMessage");
const success = document.querySelector<HTMLDialogElement>(
	"#consultationSuccess",
);
const whatsappConfirm = document.querySelector<HTMLAnchorElement>(
	"#consultationWhatsappConfirm",
);

if (
	form &&
	submitButton &&
	captchaId &&
	captchaQuestion &&
	captchaCode &&
	captchaAnswer &&
	success &&
	whatsappConfirm
) {
	const button = submitButton;
	const captchaIdField = captchaId;
	const captchaQuestionText = captchaQuestion;
	const captchaCodeText = captchaCode;
	const captchaAnswerField = captchaAnswer;
	const successDialog = success;
	const whatsappConfirmLink = whatsappConfirm;
	const submitLabel = button.querySelector("span")?.textContent?.trim() || "";
	const errorFallback = form.dataset.error || "Unable to send your request.";
	const loadingLabel = form.dataset.sending || "Sending your request...";
	const whatsappMessage = whatsappConfirmLink.dataset.whatsappMessage;
	let captchaReady = false;
	let submitted = false;

	function showMessage(text: string, isError: boolean): void {
		if (!message) return;
		message.hidden = false;
		message.className = `consultation-status ${isError ? "is-error" : "is-success"}`;
		message.textContent = text;
	}

	async function loadCaptcha(): Promise<boolean> {
		captchaReady = false;
		button.disabled = true;
		captchaQuestionText.textContent =
			document.documentElement.lang === "en"
				? "Preparing verification…"
				: "Menyiapkan verifikasi…";
		try {
			const response = await fetch(
				`/api/contact/captcha?lang=${document.documentElement.lang === "en" ? "en" : "id"}`,
				{ headers: { Accept: "application/json" } },
			);
			const result = (await response.json()) as {
				success?: boolean;
				captcha_id?: string;
				image?: string;
				question?: string;
				message?: string;
			};
			if (
				!response.ok ||
				!result.success ||
				!result.captcha_id ||
				!result.image
			) {
				throw new Error(result.message || "Verification is unavailable.");
			}
			captchaIdField.value = result.captcha_id;
			captchaCodeText.setAttribute("src", result.image);
			captchaQuestionText.textContent = result.question || "";
			captchaAnswerField.value = "";
			captchaReady = true;
			button.disabled = false;
			return true;
		} catch (error) {
			captchaQuestionText.textContent =
				document.documentElement.lang === "en"
					? "Verification could not be loaded."
					: "Verifikasi tidak dapat dimuat.";
			showMessage(
				error instanceof Error
					? error.message
					: document.documentElement.lang === "en"
						? "Please reload the verification before sending your request."
						: "Muat ulang verifikasi sebelum mengirim formulir.",
				true,
			);
			return false;
		}
	}

	function buildConsultationMessage(data: FormData): string {
		const english = document.documentElement.lang === "en";
		const fields: Array<[string, FormDataEntryValue | null]> = [
			[
				english ? "Estimated date" : "Perkiraan tanggal",
				data.get("wedding_date"),
			],
			[english ? "Wedding city" : "Kota pernikahan", data.get("wedding_city")],
			[english ? "Venue" : "Venue", data.get("venue")],
			[english ? "Estimated guests" : "Perkiraan tamu", data.get("guests")],
			[english ? "Estimated budget" : "Kisaran anggaran", data.get("budget")],
		];
		const details = fields
			.filter(([, value]) => typeof value === "string" && value.trim())
			.map(([label, value]) => `${label}: ${String(value).trim()}`);
		const services = data
			.getAll("services")
			.filter((value): value is string => typeof value === "string");
		const note = String(data.get("message") || "").trim();

		return [
			english
				? "Wedding consultation request"
				: "Permintaan konsultasi pernikahan",
			...details,
			...(services.length
				? [
						`${english ? "Services of interest" : "Layanan yang diminati"}: ${services.join(", ")}`,
					]
				: []),
			"",
			note,
		]
			.filter(
				(line, index, list) => line || (index > 0 && list[index - 1] !== ""),
			)
			.join("\n")
			.slice(0, 2000);
	}

	successDialog.addEventListener("cancel", (event) => {
		event.preventDefault();
	});

	captchaReload?.addEventListener("click", () => {
		if (message) message.hidden = true;
		void loadCaptcha();
	});

	form.addEventListener("submit", async (event) => {
		event.preventDefault();
		if (!form.reportValidity()) return;
		if (!whatsappMessage) {
			showMessage(errorFallback, true);
			return;
		}
		if (!captchaReady || !captchaIdField.value) {
			showMessage(
				document.documentElement.lang === "en"
					? "Please reload the verification before sending your request."
					: "Muat ulang verifikasi sebelum mengirim formulir.",
				true,
			);
			return;
		}
		button.disabled = true;
		button.disabled = true;
		const label = button.querySelector("span");
		if (label) label.textContent = loadingLabel;
		if (message) message.hidden = true;

		const data = new FormData(form);
		const payload = {
			nama: String(data.get("nama") || "").trim(),
			email: String(data.get("email") || "").trim(),
			no_telp: String(data.get("no_telp") || "").trim(),
			isi: buildConsultationMessage(data),
			captcha_id: captchaIdField.value,
			captcha_answer: captchaAnswerField.value,
		};

		try {
			const response = await fetch("/api/contact", {
				method: "POST",
				headers: {
					"Content-Type": "application/json",
					Accept: "application/json",
				},
				body: JSON.stringify(payload),
			});
			const result = (await response.json()) as {
				success?: boolean;
				message?: string;
			};
			if (!response.ok || !result.success) {
				throw new Error(result.message || errorFallback);
			}
			const whatsappUrl = new URL(whatsappConfirmLink.href);
			whatsappUrl.searchParams.set("text", whatsappMessage);
			whatsappConfirmLink.href = whatsappUrl.toString();
			submitted = true;
			successDialog.showModal();
			whatsappConfirmLink.focus();
		} catch (error) {
			showMessage(error instanceof Error ? error.message : errorFallback, true);
			await loadCaptcha();
		} finally {
			if (!form.hidden) {
				if (!submitted) button.disabled = !captchaReady;
				if (label) label.textContent = submitLabel;
			}
		}
	});

	void loadCaptcha();
}
