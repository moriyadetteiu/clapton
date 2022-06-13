export default class FileDownloader {
  public download(filename: string): void {
    window.location.href = `http://localhost:20080/download/${filename}`
  }
}
