<?php

namespace NFePHP\NFSe\Models\Abrasf\Factories;

use NFePHP\NFSe\Models\Abrasf\Factories\Header;
use NFePHP\NFSe\Models\Abrasf\Factories\Factory;
use NFePHP\NFSe\Models\Abrasf\RenderRPS;

class EnviarLoteRps extends Factory
{
    public function render(
        $versao,
        $remetenteTipoDoc,
        $remetenteCNPJCPF,
        $inscricaoMunicipal,
        $lote,
        $rpss
    ) {
        $method = 'EnviarLoteRpsEnvio';
        $xsd = 'nfse_recife_v01';
        $qtdRps = count($rpss);
        $content = "<EnviarLoteRpsEnvio "
            . "xmlns=\"http://www.abrasf.org.br/ABRASF/arquivos/nfse.xsd\">";
        $content .= "<LoteRps>";
        $content .= "<NumeroLote>$lote</NumeroLote>";
        //$content .= "<CpfCnpj>";
        if ($remetenteTipoDoc == '2') {
            $content .= "<Cnpj>$remetenteCNPJCPF</Cnpj>";
        } else {
            $content .= "<Cpf>$remetenteCNPJCPF</Cpf>";
        }
        //$content .= "</CpfCnpj>";
        $content .= "<InscricaoMunicipal>$inscricaoMunicipal</InscricaoMunicipal>";
        $content .= "<QuantidadeRps>$qtdRps</QuantidadeRps>";
        $content .= "<ListaRps>";
        foreach ($rpss as $rps) {
            $content .= RenderRPS::toXml($rps, $this->timezone, $this->algorithm);
        }
        $content .= "</ListaRps>";
        $content .= "</LoteRps>";
        $content .= "</EnviarLoteRpsEnvio>";
        
        $body = Signer::sign(
            $this->certificate,
            $content,
            'LoteRps',
            '',
            $this->algorithm,
            [false,false,null,null]
        );
        
        $body = $this->clear($body);
        
        $this->validar($versao, $body, 'Abrasf', $xsd, '');
        return $body;
    }
}
