from playwright.sync_api import Page, expect, sync_playwright
import os

def test_document_modal_swal(page: Page):
    cwd = os.getcwd()
    page.goto(f"file://{cwd}/mock_document_verification.html")

    # 1. Open Modal
    page.locator("button[data-target='#documentModal-1']").click()
    modal = page.locator("#documentModal-1")
    expect(modal).to_be_visible()

    # Verify Image
    expect(modal.locator("img")).to_be_visible()

    # 2. Click "Terima"
    modal.locator("button.btn-success").click()

    # 3. Verify Confirm Swal
    swal_popup = page.locator(".swal2-popup")
    expect(swal_popup).to_be_visible()
    expect(swal_popup).to_contain_text("Terima Dokumen?")

    # 4. Click Cancel to return to Modal
    page.locator(".swal2-cancel").click()
    # Modal should reappear (Bootstrap handles this, but since we hid it, we need to ensure our code re-showed it)
    # The simulation re-shows it.

    # Wait for modal to be visible again
    # Note: Bootstrap animation might take a moment.
    page.wait_for_timeout(500)
    expect(modal).to_be_visible()

    # 5. Click "Revisi"
    modal.locator("button.btn-warning").click()

    # 6. Verify Revision Swal
    expect(swal_popup).to_contain_text("Minta Revisi Dokumen")
    expect(page.locator("textarea.swal2-textarea")).to_be_visible()

    page.screenshot(path="document_verification_flow.png")

if __name__ == "__main__":
    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)
        page = browser.new_page()
        try:
            test_document_modal_swal(page)
            print("Document Verification Flow successful!")
        except Exception as e:
            print(f"Document Verification Flow failed: {e}")
        finally:
            browser.close()
