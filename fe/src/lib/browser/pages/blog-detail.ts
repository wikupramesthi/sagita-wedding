export {};

const copyBtn = document.getElementById("copyLinkBtn");
const copyLabel = copyBtn?.querySelector<HTMLElement>("[data-copy-label]");

copyBtn?.addEventListener("click", () => {
	const url = copyBtn.getAttribute("data-url") || "";
	const original = copyBtn.getAttribute("data-label") || "Copy link";
	const copied = copyBtn.getAttribute("data-copied") || "Copied!";
	const done = () => {
		if (copyLabel) copyLabel.textContent = copied;
		else copyBtn.textContent = copied;
		setTimeout(() => {
			if (copyLabel) copyLabel.textContent = original;
			else copyBtn.textContent = original;
		}, 2000);
	};

	if (navigator.clipboard?.writeText) {
		navigator.clipboard.writeText(url).then(done, done);
	} else {
		const ta = document.createElement("textarea");
		ta.value = url;
		document.body.appendChild(ta);
		ta.select();
		try {
			(document as any).execCommand("copy");
		} catch {
			// ignore
		}
		document.body.removeChild(ta);
		done();
	}
});

const shareBtn = document.getElementById("nativeShareBtn");

if (shareBtn && typeof navigator.share === "function") {
	shareBtn.hidden = false;
	shareBtn.addEventListener("click", async () => {
		const payload = {
			title: shareBtn.getAttribute("data-title") || document.title,
			text: shareBtn.getAttribute("data-title") || document.title,
			url: shareBtn.getAttribute("data-url") || window.location.href,
		};
		try {
			await navigator.share(payload);
		} catch (error) {
			if (error instanceof DOMException && error.name === "AbortError") return;
			console.error("Native share failed", error);
		}
	});
}
