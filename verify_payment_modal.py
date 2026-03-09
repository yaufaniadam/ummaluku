from playwright.sync_api import Page, expect, sync_playwright
import os
import time

def test_payment_modal_swal(page: Page):
    # Load the mock HTML file
    cwd = os.getcwd()
    page.goto(f"file://{cwd}/mock_payment_verification.html")

    # 1. Open Modal
    page.locator("button[data-target='#verificationModal-1']").click()
    modal = page.locator("#verificationModal-1")
    expect(modal).to_be_visible()

    # 2. Click "Tolak" to trigger Swal Reason Prompt
    reject_btn = modal.locator("button.btn-danger").first
    reject_btn.click()

    # 3. Verify SweetAlert Rejection Prompt appears
    swal_popup = page.locator(".swal2-popup")
    expect(swal_popup).to_be_visible()
    expect(swal_popup).to_contain_text("Tolak Pembayaran?")

    # 4. Fill in rejection reason
    reason_textarea = swal_popup.locator("textarea.swal2-textarea")
    expect(reason_textarea).to_be_visible()
    reason_textarea.fill("Bukti pembayaran tidak jelas.")

    # 5. Click Confirm Reject
    swal_confirm_btn = swal_popup.locator(".swal2-confirm")
    swal_confirm_btn.click()

    # 6. Verify Loading State
    expect(swal_popup).to_contain_text("Memproses...")

    # Take screenshot of loading state after rejection
    page.screenshot(path="payment_rejection_swal.png")

if __name__ == "__main__":
    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)
        page = browser.new_page()
        try:
            test_payment_modal_swal(page)
            print("Swal Verification successful!")
        except Exception as e:
            print(f"Swal Verification failed: {e}")
        finally:
            browser.close()
