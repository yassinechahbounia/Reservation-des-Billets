<?php
// Charger Dompdf depuis Composer
require_once __DIR__ . '/../vendor/autoload.php';
use Dompdf\Dompdf;

// Récupérer les infos depuis GET
$cv = $_GET['cv'] ?? '';
$db = $_GET['db'] ?? '';
$nbp = $_GET['np'] ?? 1;
$email = $_GET['email'] ?? '';

// Créer le contenu HTML du PDF
$html = '<style>
body { font-family: Arial, sans-serif; padding: 20px; }
.ticket { border: 2px solid #1e40af; border-radius: 8px; padding: 20px; margin-bottom: 30px; background: #f8fafc; }
.header { background: #1e40af; color: white; padding: 15px; margin: -20px -20px 20px -20px; border-radius: 6px 6px 0 0; }
h1 { color: white; font-size: 24px; margin: 0 0 10px 0; }
.info-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #e5e7eb; }
.info-label { font-weight: bold; color: #374151; }
.info-value { color: #1e40af; }
hr { border: none; border-top: 2px dashed #cbd5e1; margin: 20px 0; }
</style>';

for ($i = 1; $i <= $nbp; $i++) {
    $html .= '<div class="ticket">';
    $html .= '<div class="header"><h1>ONCF - Billet N° '.$i.'</h1></div>';
    $html .= '<div class="info-row"><span class="info-label">Code Voyage:</span><span class="info-value">'.htmlspecialchars($cv).'</span></div>';
    $html .= '<div class="info-row"><span class="info-label">Date de Voyage:</span><span class="info-value">'.htmlspecialchars($db).'</span></div>';
    $html .= '<div class="info-row"><span class="info-label">Email:</span><span class="info-value">'.htmlspecialchars($email).'</span></div>';
    $html .= '<hr><p style="text-align:center;color:#6b7280;font-size:12px;margin-top:20px;">Merci d\'avoir choisi ONCF pour votre voyage</p>';
    $html .= '</div>';
}

// Générer le PDF avec Dompdf
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Télécharger automatiquement
$dompdf->stream("billet_$cv.pdf", ["Attachment" => true]);
exit;
