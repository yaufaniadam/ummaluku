from playwright.sync_api import Page, expect, sync_playwright
import os

def test_payment_modal(page: Page):
    # Load the mock HTML file
    cwd = os.getcwd()
    page.goto(f"file://{cwd}/mock_payment_verification.html")

    # Verify the table is visible
    expect(page.locator("table")).to_be_visible()

    # 1. Test Installment 1 (With Image)
    # Click "Verifikasi"
    verify_btn = page.locator("button[data-target='#verificationModal-1']")
    verify_btn.click()

    # Wait for modal to appear
    modal = page.locator("#verificationModal-1")
    expect(modal).to_be_visible()

    # Verify image is present
    expect(modal.locator("img")).to_be_visible()

    # Verify buttons
    expect(modal.locator("button.btn-success")).to_have_text("Setujui")
    expect(modal.locator("button.btn-danger")).to_have_text("Tolak")

    # Close modal
    modal.locator("button[data-dismiss='modal']").first.click() # Using .first because there is also the 'X' button

    # 2. Test Installment 3 (No Image)
    verify_btn_3 = page.locator("button[data-target='#verificationModal-3']")
    verify_btn_3.click()

    modal_3 = page.locator("#verificationModal-3")
    expect(modal_3).to_be_visible()

    # Verify warning message
    expect(modal_3.locator("p.text-danger")).to_have_text("Tidak ada bukti pembayaran yang diunggah.")

    # Screenshot
    page.screenshot(path="payment_verification_modal.png")

if __name__ == "__main__":
    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)
        page = browser.new_page()
        try:
            test_payment_modal(page)
            print("Verification successful!")
        except Exception as e:
            print(f"Verification failed: {e}")
        finally:
            browser.close()
