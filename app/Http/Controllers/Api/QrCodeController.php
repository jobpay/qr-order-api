<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Symfony\Component\HttpFoundation\Response;

class QrCodeController extends Controller
{
    /**
     * QR コードを生成して返却
     *
     * @return Response
     */
    public function generate()
    {
        // QR コードの内容を設定
        $qrCode = QrCode::create('https://www.example.com')
            ->setSize(300) // QRコードのサイズ
            ->setMargin(10); // 余白のサイズ

        // QR コードを PNG 形式で出力
        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        // QR コードのバイナリデータを取得してBASE64エンコードする
        $output = "data:image/png;base64,".base64_encode($result->getString());

        // レスポンスを生成（画像として返却）
        return new Response($output, Response::HTTP_OK, ['Content-Type' => 'image/png']);
    }
}
