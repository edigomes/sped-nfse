<?php

namespace NFePHP\NFSe\Models\Abrasf\Factories;

use NFePHP\NFSe\Models\Abrasf\Factories\Header;
use NFePHP\NFSe\Models\Abrasf\Factories\Factory;
use NFePHP\NFSe\Models\Abrasf\RenderRPS;

class CancelarNfse extends Factory
{
    public function render(
        $versao,
        $numero,
        $remetenteCNPJCPF,
        $inscricaoMunicipal,
        $codigoMunicipio,
        $codigoCancelamento
    ) {
        $method = 'CancelarNfse';
        $xsd = 'servico_cancelar_nfse_envio';
        $content  = "<{$method}Envio xmlns=\"http://www.abrasf.org.br/ABRASF/arquivos/nfse.xsd\">";
        $content .= "<Pedido>";
        $content .= "<InfPedidoCancelamento>";
        $content .= "<IdentificacaoNfse>";
        $content .= "<Numero>$numero</Numero>";
        $content .= "<Cnpj>$remetenteCNPJCPF</Cnpj>";
        $content .= "<InscricaoMunicipal>$inscricaoMunicipal</InscricaoMunicipal>";
        $content .= "<CodigoMunicipio>$codigoMunicipio</CodigoMunicipio>";
        $content .= "</IdentificacaoNfse>";
        $content .= "<CodigoCancelamento>$codigoCancelamento</CodigoCancelamento>";
        $content .= "</InfPedidoCancelamento>";
        $content .= "</Pedido>";
        $content .= "</{$method}Envio>";
        
        /*$body = Signer::sign(
            $this->certificate,
            $content,
            'LoteRps',
            '',
            $this->algorithm,
            [false,false,null,null]
        );*/
        
        $body = $this->clear($content);
        $this->validar($versao, $body, 'Abrasf', $xsd, '');
        return $body;
    }
}
