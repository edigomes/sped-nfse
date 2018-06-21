<?php


namespace NFePHP\NFSe\Models\Abrasf\Factories;

use NFePHP\NFSe\Models\Abrasf\Factories\Header;
use NFePHP\NFSe\Models\Abrasf\Factories\Factory;
use NFePHP\NFSe\Models\Abrasf\RenderRPS;

class GerarNfse extends Factory
{
    public function render(
        $versao,
        $remetenteTipoDoc,
        $remetenteCNPJCPF,
        $inscricaoMunicipal,
        $lote,
        $rpss
    ) {
        $method = 'GerarNfse';
        $xsd = 'nfse_recife_v01';
        $content = "<GerarNfseEnvio "
            . "xmlns=\"http://nfse.recife.pe.gov.br/WSNacional/XSD/1/nfse_recife_v01.xsd\">";
        foreach ($rpss as $rps) {
            $content .= RenderRPS::toXml($rps, $this->timezone, $this->algorithm);
        }
        $content .= "</GerarNfseEnvio>";
        
        /*$body = Signer::sign(
            $this->certificate,
            $content,
            'LoteRps',
            '',
            $this->algorithm,
            [false,false,null,null]
        );*/
        
        $body = $this->clear($content);
        //$this->validar($versao, $body, 'Abrasf', $xsd, '');
        return $body;
    }
}
