<?php

namespace NFePHP\NFSe\Models\Abrasf;

/**
 * Classe para a comunicação com os webservices
 * conforme o modelo ABRASF 2.03
 *
 * @category  NFePHP
 * @package   NFePHP\NFSe\Models\Abrasf\Tools
 * @copyright NFePHP Copyright (c) 2016
 * @license   http://www.gnu.org/licenses/lgpl.txt LGPLv3+
 * @license   https://opensource.org/licenses/MIT MIT
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-nfse for the canonical source repository
 */

use SimpleXMLElement;
use NFePHP\NFSe\Models\Abrasf\Rps;
use NFePHP\NFSe\Models\Abrasf\Factories;
use NFePHP\NFSe\Common\Tools as ToolsBase;

class Tools extends ToolsBase
{
    public function cancelarNfse($numero, $codigoCancelamento)
    {
        $this->method = 'CancelarNfse';
        $fact = new Factories\CancelarNfse($this->certificate);
        $fact->setSignAlgorithm($this->algorithm);
        $cmun = $this->config->cmun;
        if ($this->config->tpAmb == 2) {
            $cmun = '999';
        }
        $message = $fact->render(
            $this->config->versao,
            $this->remetenteTipoDoc,
            $this->remetenteCNPJCPF,
            $this->remetenteIM,
            $cmun,
            $numero,
            $codigoCancelamento
        );
        return $this->sendRequest('', $message);
    }
    
    public function consultarUrlVisualizacaoNfse($numero, $codigoTributacao)
    {
        $this->method = 'ConsultarUrlVisualizacaoNfse';
        $fact = new Factories\ConsultarUrlVisualizacaoNfse($this->certificate);
        $fact->setSignAlgorithm($this->algorithm);
        $message = $fact->render(
            $this->config->versao,
            $this->remetenteTipoDoc,
            $this->remetenteCNPJCPF,
            $this->remetenteIM,
            $numero,
            $codigoTributacao
        );
        return $this->sendRequest('', $message);
    }
    
    public function consultarUrlVisualizacaoNfseSerie($numero, $codigoTributacao, $serie)
    {
        $this->method = 'ConsultarUrlVisualizacaoNfseSerie';
        $fact = new Factories\ConsultarUrlVisualizacaoNfse($this->certificate);
        $fact->setSignAlgorithm($this->algorithm);
        $message = $fact->render(
            $this->config->versao,
            $this->remetenteTipoDoc,
            $this->remetenteCNPJCPF,
            $this->remetenteIM,
            $numero,
            $codigoTributacao,
            $serie
        );
        return $this->sendRequest('', $message);
    }
    
    public function recepcionarLoteRps($lote, $rpss)
    {
        $this->method = 'RecepcionarLoteRps';
        $fact = new Factories\EnviarLoteRps($this->certificate);
        $fact->setSignAlgorithm($this->algorithm);
        $fact->setTimezone($this->timezone);
        $message = $fact->render(
            $this->config->versao,
            $this->remetenteTipoDoc,
            $this->remetenteCNPJCPF,
            $this->remetenteIM,
            $lote,
            $rpss
        );
        return $this->sendRequest('', $message);
    }
    
    public function gerarNfse($lote, $rpss)
    {
        $this->method = 'GerarNfse';
        $fact = new Factories\GerarNfse($this->certificate);
        $fact->setSignAlgorithm($this->algorithm);
        $fact->setTimezone($this->timezone);
        $message = $fact->render(
            $this->config->versao,
            $this->remetenteTipoDoc,
            $this->remetenteCNPJCPF,
            $this->remetenteIM,
            $lote,
            $rpss
        );
        return $this->sendRequest('', $message);
    }

    public function consultarNfse(
        $numeroNFSe = '',
        $dtInicio = '',
        $dtFim = '',
        $tomador = [],
        $intermediario = []
    ) {
        $this->method = 'ConsultarNfse';
        $fact = new Factories\ConsultarNfse($this->certificate);
        $fact->setSignAlgorithm($this->algorithm);
        $message = $fact->render(
            $this->config->versao,
            $this->remetenteTipoDoc,
            $this->remetenteCNPJCPF,
            $this->remetenteIM,
            $numeroNFSe,
            $dtInicio,
            $dtFim,
            $tomador,
            $intermediario
        );
        return $this->sendRequest('', $message);
    }
    
    public function consultarNfseRps($numero, $serie, $tipo)
    {
        $this->method = 'ConsultarNfseRps';
        $fact = new Factories\ConsultarNfseRps($this->certificate);
        $fact->setSignAlgorithm($this->algorithm);
        $message = $fact->render(
            $this->config->versao,
            $this->remetenteTipoDoc,
            $this->remetenteCNPJCPF,
            $this->remetenteIM,
            $numero,
            $serie,
            $tipo
        );
        return $this->sendRequest('', $message);
    }
    
    public function consultarLoteRps($protocolo)
    {
        $this->method = 'ConsultarLoteRps';
        $fact = new Factories\ConsultarLoteRps($this->certificate);
        $fact->setSignAlgorithm($this->algorithm);
        $message = $fact->render(
            $this->config->versao,
            $this->remetenteTipoDoc,
            $this->remetenteCNPJCPF,
            $this->remetenteIM,
            $protocolo
        );
        return $this->sendRequest('', $message);
    }
    
    public function consultarSituacaoLoteRps($protocolo)
    {
        $this->method = 'ConsultarSituacaoLoteRPS';
        $fact = new Factories\ConsultarSituacaoLoteRps($this->certificate);
        $fact->setSignAlgorithm($this->algorithm);
        $message = $fact->render(
            $this->config->versao,
            $this->remetenteTipoDoc,
            $this->remetenteCNPJCPF,
            $this->remetenteIM,
            $protocolo
        );
        return $this->sendRequest('', $message);
    }
    
    protected function sendRequest($url, $message)
    {
        //no caso do ISSNET o URL é unico para todas as ações
        $url = $this->url[$this->config->tpAmb];
        if (!is_object($this->soap)) {
            $this->soap = new \NFePHP\NFSe\Common\SoapCurl($this->certificate);
        }
        //formata o xml da mensagem para o padão esperado pelo webservice
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($message);
        /*$message = str_replace('<?xml version="1.0"?>', '<?xml version="1.0" encoding="UTF-8"?>', $dom->saveXML());*/
        $message = str_replace('<?xml version="1.0"?>', '', $dom->saveXML());
        
        if ($this->withcdata) {
            $messageText = $this->stringTransform($message);
        } else {
            $messageText = $message;
        }
        
        $request = "<". $this->method . "Request xmlns=\"".$this->xmlns."\">"
            . "<inputXML>$messageText</inputXML>"
            . "</". $this->method . "Request>";
        $params = [
            'xml' => $message
        ];
        
        $action = "\"". $this->xmlns ."". $this->method ."\"";
        
        $resp = $this->soap->send(
            $url,
            $this->method,
            $action,
            $this->soapversion,
            $params,
            $this->namespaces[$this->soapversion],
            $request
        );
        return html_entity_decode($resp);
    }
    
    public function standardize($ret_soap) {
        
        // Lê a estrutura do retorno soap
        $xml = new \SimpleXMLElement($ret_soap);
        $ns = $xml->getNamespaces(true);
        
        // Verifica se o retorno é um soap ou um xml direto
        if (isset($ns['soapenv'])) {
            $soap = $xml->children($ns['soapenv']);
            $res = $soap->Body->children($ns[""]);
        } else if (isset($ns['soap'])) {
            $soap = $xml->children($ns['soap']);
            $res = $soap->Body->children($ns[""]);
        } else {
            $res = $xml;
        }

        // Retorno em xml
        $aRetorno = simplexml_load_string($res->asXml());
        
        return json_decode(json_encode($aRetorno));
        
    }
    
}

