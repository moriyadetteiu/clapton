export default class FileDownloader {
  private static baseUrl: string

  public static setBaseUrl(baseUrl: string): void {
    FileDownloader.baseUrl = baseUrl
  }

  public download(filename: string): void {
    window.location.href = `${FileDownloader.baseUrl}/download/${filename}`
  }
}
