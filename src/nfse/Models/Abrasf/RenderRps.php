<?php

namespace NFePHP\NFSe\Models\Abrasf;

/**
 * Classe para a renderização do XML dos RPS
 * para o Objeto RPS no modelo ABRASF 2.03
 *
 * @category  NFePHP
 * @package   NFePHP\NFSe\Models\Abrasf\RenderRps
 * @copyright NFePHP Copyright (c) 2016
 * @license   http://www.gnu.org/licenses/lgpl.txt LGPLv3+
 * @license   https://opensource.org/licenses/MIT MIT
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-nfse for the canonical source repository
 */

use NFePHP\Common\DOMImproved as Dom;
use NFePHP\NFSe\Models\Abrasf\Rps;
use NFePHP\Common\Certificate;

class RenderRPS
{
    /**
     * @var DOMImproved
     */
    protected static $dom;
    /**
     * @var Certificate
     */
    protected static $certificate;
    /**
     * @var int
     */
    protected static $algorithm;
    /**
     * @var \DateTimeZone
     */
    protected static $timezone;

    public static function toXml($data, \DateTimeZone $timezone, $algorithm = OPENSSL_ALGO_SHA1)
    {
        //self::$certificate = $certificate;
        self::$algorithm = $algorithm;
        self::$timezone = $timezone;
        $xml = '';
        if (is_object($data)) {
            return self::render($data);
        } elseif (is_array($data)) {
            foreach ($data as $rps) {
                $xml .= self::render($rps);
            }
        }
        return $xml;
    }
    
    /**
     * Monta o xml com base no objeto Rps
     * @param Rps $rps
     * @return string
     */
    private static function render(Rps $rps)
    {
        self::$dom = new Dom('1.0', 'utf-8');
        $root = self::$dom->createElement('Rps');
        $infRPS = self::$dom->createElement('InfRps');
        $infRPS->setAttribute("xmlns", "http://www.abrasf.org.br/ABRASF/arquivos/nfse.xsd");
        $infRPS->setAttribute("Id", "Rps_{$rps->infNumero}");
        
        $identificacaoRps = self::$dom->createElement('IdentificacaoRps');
        self::$dom->addChild(
            $identificacaoRps,
            'Numero',
            $rps->infNumero,
            true,
            "Numero do RPS",
            true
        );
        self::$dom->addChild(
            $identificacaoRps,
            'Serie',
            $rps->infSerie,
            true,
            "Serie do RPS",
            true
        );
        self::$dom->addChild(
            $identificacaoRps,
            'Tipo',
            $rps->infTipo,
            true,
            "Tipo do RPS",
            true
        );
        self::$dom->appChild($infRPS, $identificacaoRps, 'Adicionando tag IdentificacaoRPS');
        $rps->infDataEmissao->setTimezone(self::$timezone);
        self::$dom->addChild(
            $infRPS,
            'DataEmissao',
            $rps->infDataEmissao->format('Y-m-d\TH:i:s'),
            true,
            'Data de Emissão do RPS',
            false
        );
        self::$dom->addChild(
            $infRPS,
            'NaturezaOperacao',
            $rps->infNaturezaOperacao,
            true,
            'Natureza da operação',
            false
        );
        self::$dom->addChild(
            $infRPS,
            'OptanteSimplesNacional',
            $rps->infOptanteSimplesNacional,
            true,
            'OptanteSimplesNacional',
            false
        );
        self::$dom->addChild(
            $infRPS,
            'IncentivadorCultural',
            $rps->infIncentivadorCultural,
            true,
            'IncentivadorCultural',
            false
        );
        self::$dom->addChild(
            $infRPS,
            'Status',
            $rps->infStatus,
            true,
            'Status',
            false
        );
        
        if (!empty($rps->infRpsSubstituido['numero'])) {
            $rpssubs = self::$dom->createElement('RpsSubstituido');
            self::$dom->addChild(
                $rpssubs,
                'Numero',
                $rps->infRpsSubstituido['numero'],
                true,
                'Numero',
                false
            );
            self::$dom->addChild(
                $rpssubs,
                'Serie',
                $rps->infRpsSubstituido['serie'],
                true,
                'Serie',
                false
            );
            self::$dom->addChild(
                $rpssubs,
                'Tipo',
                $rps->infRpsSubstituido['tipo'],
                true,
                'tipo',
                false
            );
            self::$dom->appChild($infRPS, $rpssubs, 'Adicionando tag RpsSubstituido em infRps');
        }
        
        self::$dom->addChild(
            $infRPS,
            'RegimeEspecialTributacao',
            $rps->infRegimeEspecialTributacao,
            true,
            'RegimeEspecialTributacao',
            false
        );
        $servico = self::$dom->createElement('Servico');
        $valores = self::$dom->createElement('Valores');
        self::$dom->addChild(
            $valores,
            'ValorServicos',
            $rps->infValorServicos,
            true,
            'ValorServicos',
            false
        );
        self::$dom->addChild(
            $valores,
            'ValorDeducoes',
            $rps->infValorDeducoes,
            false,
            'ValorDeducoes',
            false
        );
        self::$dom->addChild(
            $valores,
            'ValorPis',
            $rps->infValorPis,
            false,
            'ValorPis',
            false
        );
        self::$dom->addChild(
            $valores,
            'ValorCofins',
            $rps->infValorCofins,
            false,
            'ValorCofins',
            false
        );
        self::$dom->addChild(
            $valores,
            'ValorInss',
            $rps->infValorInss,
            false,
            'ValorInss',
            false
        );
        self::$dom->addChild(
            $valores,
            'ValorIr',
            $rps->infValorIr,
            false,
            'ValorIr',
            false
        );
        self::$dom->addChild(
            $valores,
            'ValorCsll',
            $rps->infValorCsll,
            false,
            'ValorCsll',
            false
        );
        self::$dom->addChild(
            $valores,
            'IssRetido',
            $rps->infIssRetido,
            true,
            'IssRetido',
            false
        );
        self::$dom->addChild(
            $valores,
            'ValorIss',
            $rps->infValorIss,
            false,
            'ValorIss',
            false
        );
        self::$dom->addChild(
            $valores,
            'ValorIssRetido',
            $rps->infValorIssRetido,
            false,
            'ValorIssRetido',
            false
        );
        self::$dom->addChild(
            $valores,
            'OutrasRetencoes',
            $rps->infOutrasRetencoes,
            false,
            'OutrasRetencoes',
            false
        );
        self::$dom->addChild(
            $valores,
            'BaseCalculo',
            $rps->infBaseCalculo,
            false,
            'BaseCalculo',
            false
        );
        self::$dom->addChild(
            $valores,
            'Aliquota',
            number_format($rps->infAliquota, 2, '.', ''),
            false,
            'Aliquota',
            false
        );
        self::$dom->addChild(
            $valores,
            'ValorLiquidoNfse',
            $rps->infValorLiquidoNfse,
            false,
            'ValorLiquidoNfse',
            false
        );
        self::$dom->addChild(
            $valores,
            'DescontoIncondicionado',
            $rps->infDescontoIncondicionado,
            false,
            'DescontoIncondicionado',
            false
        );
        self::$dom->addChild(
            $valores,
            'DescontoCondicionado',
            $rps->infDescontoCondicionado,
            false,
            'DescontoCondicionado',
            false
        );
        self::$dom->appChild($servico, $valores, 'Adicionando tag Valores em Servico');
        
        self::$dom->addChild(
            $servico,
            'ItemListaServico',
            $rps->infItemListaServico,
            true,
            'ItemListaServico',
            false
        );
        self::$dom->addChild(
            $servico,
            'CodigoCnae',
            $rps->infCodigoCnae,
            true,
            'CodigoCnae',
            false
        );
        self::$dom->addChild(
            $servico,
            'CodigoTributacaoMunicipio',
            $rps->infCodigoTributacaoMunicipio,
            true,
            'CodigoTributacaoMunicipio',
            false
        );
        self::$dom->addChild(
            $servico,
            'Discriminacao',
            $rps->infDiscriminacao,
            true,
            'Discriminacao',
            false
        );
        self::$dom->addChild(
            $servico,
            'CodigoMunicipio',
            $rps->infCodigoMunicipio,
            true,
            'CodigoMunicipio',
            false
        );
        self::$dom->addChild(
            $servico,
            'MunicipioPrestacaoServico',
            $rps->infMunicipioPrestacaoServico,
            true,
            'MunicipioPrestacaoServico',
            false
        );
        self::$dom->appChild($infRPS, $servico, 'Adicionando tag Servico');

        $prestador = self::$dom->createElement('Prestador');
        //$identificacaoPrestador = self::$dom->createElement('IdentificacaoPrestador');
        
        if ($rps->infPrestador['tipo'] == 2) {
            self::$dom->addChild(
                $prestador,
                'Cnpj',
                $rps->infPrestador['cnpjcpf'],
                true,
                'Prestador CNPJ',
                false
            );
        } else {
            self::$dom->addChild(
                $prestador,
                'Cpf',
                $rps->infPrestador['cnpjcpf'],
                true,
                'Prestador CPF',
                false
            );
        }
        self::$dom->addChild(
            $prestador,
            'InscricaoMunicipal',
            $rps->infPrestador['im'],
            true,
            'InscricaoMunicipal',
            false
        );
        
        //self::$dom->appChild($prestador, $identificacaoPrestador, 'Adicionando tag IdentificacaoPrestador em PrestadorServico');
        
        /*self::$dom->addChild(
            $prestador,
            'RazaoSocial',
            $rps->infPrestador['razao'],
            true,
            'Prestador Razão Social',
            false
        );*/
        
        /*$enderecoPrestador = self::$dom->createElement('Endereco');
        self::$dom->addChild(
            $enderecoPrestador,
            'Endereco',
            $rps->infPrestadorEndereco['end'],
            true,
            'Endereco',
            false
        );
        self::$dom->addChild(
            $enderecoPrestador,
            'Numero',
            $rps->infPrestadorEndereco['numero'],
            true,
            'Numero',
            false
        );
        self::$dom->addChild(
            $enderecoPrestador,
            'Complemento',
            $rps->infPrestadorEndereco['complemento'],
            true,
            'Complemento',
            false
        );
        self::$dom->addChild(
            $enderecoPrestador,
            'Bairro',
            $rps->infPrestadorEndereco['bairro'],
            true,
            'Bairro',
            false
        );
        self::$dom->addChild(
            $enderecoPrestador,
            'CodigoMunicipio',
            $rps->infPrestadorEndereco['cmun'],
            true,
            'CodigoMunicipio',
            false
        );
        self::$dom->addChild(
            $enderecoPrestador,
            'Uf',
            $rps->infPrestadorEndereco['uf'],
            true,
            'Uf',
            false
        );
        self::$dom->addChild(
            $enderecoPrestador,
            'Cep',
            $rps->infPrestadorEndereco['cep'],
            true,
            'Cep',
            false
        );
        self::$dom->appChild($prestador, $enderecoPrestador, 'Adicionando tag Endereco em Prestador');*/
      
        /*if ($rps->infPrestador['tel'] != '' || $rps->infPrestador['email'] != '') {
            $contato = self::$dom->createElement('Contato');
            self::$dom->addChild(
                $contato,
                'Telefone',
                $rps->infPrestador['tel'],
                false,
                'Telefone Prestador',
                false
            );
            self::$dom->addChild(
                $contato,
                'Email',
                $rps->infPrestador['email'],
                false,
                'Email Prestador',
                false
            );
            self::$dom->appChild($prestador, $contato, 'Adicionando tag Contato em Prestador');
        }*/
        
        self::$dom->appChild($infRPS, $prestador, 'Adicionando tag Prestador em infRPS');
        
        $tomador = self::$dom->createElement('Tomador');
        $identificacaoTomador = self::$dom->createElement('IdentificacaoTomador');
        $cpfCnpjTomador = self::$dom->createElement('CpfCnpj');
        if ($rps->infTomador['tipo'] == 2) {
            self::$dom->addChild(
                $cpfCnpjTomador,
                'Cnpj',
                $rps->infTomador['cnpjcpf'],
                true,
                'Tomador CNPJ',
                false
            );
        } else {
            self::$dom->addChild(
                $cpfCnpjTomador,
                'Cpf',
                $rps->infTomador['cnpjcpf'],
                true,
                'Tomador CPF',
                false
            );
        }
        self::$dom->appChild($identificacaoTomador, $cpfCnpjTomador, 'Adicionando tag CpfCnpj em IdentificacaTomador');
        self::$dom->appChild($tomador, $identificacaoTomador, 'Adicionando tag IdentificacaoTomador em Tomador');
        self::$dom->addChild(
            $tomador,
            'RazaoSocial',
            $rps->infTomador['razao'],
            true,
            'RazaoSocial',
            false
        );
        $endereco = self::$dom->createElement('Endereco');
        self::$dom->addChild(
            $endereco,
            'Endereco',
            $rps->infTomadorEndereco['end'],
            true,
            'Endereco',
            false
        );
        self::$dom->addChild(
            $endereco,
            'Numero',
            $rps->infTomadorEndereco['numero'],
            true,
            'Numero',
            false
        );
        self::$dom->addChild(
            $endereco,
            'Complemento',
            $rps->infTomadorEndereco['complemento'],
            true,
            'Complemento',
            false
        );
        self::$dom->addChild(
            $endereco,
            'Bairro',
            $rps->infTomadorEndereco['bairro'],
            true,
            'Bairro',
            false
        );
        self::$dom->addChild(
            $endereco,
            'CodigoMunicipio',
            $rps->infTomadorEndereco['cmun'],
            true,
            'CodigoMunicipio',
            false
        );
        self::$dom->addChild(
            $endereco,
            'Uf',
            $rps->infTomadorEndereco['uf'],
            true,
            'Uf',
            false
        );
        self::$dom->addChild(
            $endereco,
            'Cep',
            $rps->infTomadorEndereco['cep'],
            true,
            'Cep',
            false
        );
        self::$dom->appChild($tomador, $endereco, 'Adicionando tag Endereco em Tomador');
        
        if ($rps->infTomador['tel'] != '' || $rps->infTomador['email'] != '') {
            $contato = self::$dom->createElement('Contato');
            self::$dom->addChild(
                $contato,
                'Telefone',
                $rps->infTomador['tel'],
                false,
                'Telefone Tomador',
                false
            );
            self::$dom->addChild(
                $contato,
                'Email',
                $rps->infTomador['email'],
                false,
                'Email Tomador',
                false
            );
            self::$dom->appChild($tomador, $contato, 'Adicionando tag Contato em Tomador');
        }
        self::$dom->appChild($infRPS, $tomador, 'Adicionando tag Tomador em infRPS');
        
        if (!empty($rps->infIntermediario['razao'])) {
            $intermediario = self::$dom->createElement('IntermediarioServico');
            self::$dom->addChild(
                $intermediario,
                'RazaoSocial',
                $rps->infIntermediario['razao'],
                true,
                'Razao Intermediario',
                false
            );
            $cpfCnpj = self::$dom->createElement('CpfCnpj');
            if ($rps->infIntermediario['tipo'] == 2) {
                self::$dom->addChild(
                    $cpfCnpj,
                    'Cnpj',
                    $rps->infIntermediario['cnpjcpf'],
                    true,
                    'CNPJ Intermediario',
                    false
                );
            } elseif ($rps->infIntermediario['tipo'] == 1) {
                self::$dom->addChild(
                    $cpfCnpj,
                    'Cpf',
                    $rps->infIntermediario['cnpjcpf'],
                    true,
                    'CPF Intermediario',
                    false
                );
            }
            self::$dom->appChild($intermediario, $cpfCnpj, 'Adicionando tag CpfCnpj em Intermediario');
            self::$dom->addChild(
                $intermediario,
                'InscricaoMunicipal',
                $rps->infIntermediario['im'],
                false,
                'IM Intermediario',
                false
            );
            self::$dom->appChild($infRPS, $intermediario, 'Adicionando tag Intermediario em infRPS');
        }
        if (!empty($rps->infConstrucaoCivil['obra'])) {
            $construcao = self::$dom->createElement('ContrucaoCivil');
            self::$dom->addChild(
                $construcao,
                'CodigoObra',
                $rps->infConstrucaoCivil['obra'],
                true,
                'Codigo da Obra',
                false
            );
            self::$dom->addChild(
                $construcao,
                'Art',
                $rps->infConstrucaoCivil['art'],
                true,
                'Art da Obra',
                false
            );
            self::$dom->appChild($infRPS, $construcao, 'Adicionando tag Construcao em infRPS');
        }
        
        self::$dom->appChild($root, $infRPS, 'Adicionando tag infRPS em RPS');
        self::$dom->appendChild($root);
        $xml = str_replace('<?xml version="1.0" encoding="utf-8"?>', '', self::$dom->saveXML());
        return $xml;
    }
}
