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

    # 2. Click "Setujui" to trigger Swal Confirm
    approve_btn = modal.locator("button.btn-success").first
    approve_btn.click()

    # 3. Verify SweetAlert Confirmation appears
    swal_popup = page.locator(".swal2-popup")
    expect(swal_popup).to_be_visible()
    expect(swal_popup).to_contain_text("Setujui Pembayaran?")

    # 4. Click Confirm
    swal_confirm_btn = page.locator(".swal2-confirm")
    swal_confirm_btn.click()

    # 5. Verify Loading State
    # We expect the title to change to "Memproses..."
    expect(swal_popup).to_contain_text("Memproses...")

    # Take screenshot of loading state
    page.screenshot(path="payment_verification_swal.png")

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
