<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

//MODULO: caixa
//CLASSE DA ENTIDADE numpref
class cl_numpref {
    // cria variaveis de erro
    var $rotulo     = null;
    var $query_sql  = null;
    var $numrows    = 0;
    var $numrows_incluir = 0;
    var $numrows_alterar = 0;
    var $numrows_excluir = 0;
    var $erro_status= null;
    var $erro_sql   = null;
    var $erro_banco = null;
    var $erro_msg   = null;
    var $erro_campo = null;
    var $pagina_retorno = null;
    // cria variaveis do arquivo
    var $k03_anousu = 0;
    var $k03_instit = 0;
    var $k03_numpre = 0;
    var $k03_defope = 0;
    var $k03_recjur = 0;
    var $k03_numsli = 0;
    var $k03_impend = 'f';
    var $k03_unipri = 'f';
    var $k03_codbco = 0;
    var $k03_codage = null;
    var $k03_recmul = 0;
    var $k03_calrec = 'f';
    var $k03_msg = null;
    var $k03_msgcarne = null;
    var $k03_msgbanco = null;
    var $k03_certissvar = 'f';
    var $k03_diasjust = 0;
    var $k03_reccert = 'f';
    var $k03_taxagrupo = 0;
    var $k03_tipocodcert = 0;
    var $k03_reciboprot = 0;
    var $k03_regracnd = 0;
    var $k03_reciboprotretencao = 0;
    var $k03_tipocertidao = 0;
    var $k03_separajurmulparc = 0;
    var $k03_respcgm = 0;
    var $k03_respcargo = 0;
    var $k03_msgautent = null;
    var $k03_toleranciapgtoparc = 0;
    var $k03_pgtoparcial = 'f';
    var $k03_reemissaorecibo = 'f';
    var $k03_opcaoemissparcela = null;
    var $k03_numprepgtoparcial = 0;
    var $k03_agrupadorarquivotxtbaixabanco = 0;
    var $k03_receitapadraocredito = 0;
    var $k03_diasvalidadecertidao = 0;
    var $k03_diasreemissaocertidao = 0;
    var $k03_toleranciacredito = 0;
    // cria propriedade com as variaveis do arquivo
    var $campos = "
                 k03_anousu = int4 = Exercício 
                 k03_instit = int4 = Cód. Instituição 
                 k03_numpre = int4 = Numeração 
                 k03_defope = int4 = Operação 
                 k03_recjur = int4 = Receita Juros 
                 k03_numsli = int4 = Slip 
                 k03_impend = bool = Imprime Endereço 
                 k03_unipri = bool = Única/Primeira 
                 k03_codbco = int4 = Banco 
                 k03_codage = char(5) = Agência 
                 k03_recmul = int4 = Receita Multa 
                 k03_calrec = bool = Receita Cálculo 
                 k03_msg = text = Mensagem 
                 k03_msgcarne = text = Mensagem exibida no carne 
                 k03_msgbanco = text = Mensagem do local de pagamento exibida no carne 
                 k03_certissvar = bool = Libera Variável 
                 k03_diasjust = int4 = Dias Justif. 
                 k03_reccert = bool = Recibo na certidão 
                 k03_taxagrupo = int4 = Código do grupo de taxas 
                 k03_tipocodcert = int4 = Tipo de Codificação 
                 k03_reciboprot = int4 = Tipo do Recibo do Protocolo 
                 k03_regracnd = int4 = Regra paraEmissão CND 
                 k03_reciboprotretencao = int4 = Tipo Recibo Retenção 
                 k03_tipocertidao = int4 = Forma Emissão Certidão de Débitos 
                 k03_separajurmulparc = int4 = Separar jur e mul no parcelamento 
                 k03_respcgm = int4 = Numcgm 
                 k03_respcargo = int4 = Cargo 
                 k03_msgautent = text = Mensagem Impressora Térmica 
                 k03_toleranciapgtoparc = numeric(15,2) = Valor Tolerância Diferença Pagamento 
                 k03_pgtoparcial = bool = Ativa Pagamento Parcial 
                 k03_reemissaorecibo = bool = Permite Reemissão de Recibo 
                 k03_opcaoemissparcela = char(1) = Parcela de Outros Exercicios 
                 k03_numprepgtoparcial = int8 = Numpre inicio pagamento parcial 
                 k03_agrupadorarquivotxtbaixabanco = int4 = Forma de Processamento de Arquivo TXT 
                 k03_receitapadraocredito = int4 = Receita 
                 k03_diasvalidadecertidao = int4 = Dias para vencimento das Certidões 
                 k03_diasreemissaocertidao = int4 = Dias para reemissão das Certidões 
                 k03_toleranciacredito = float8 = Tolerãncia para Crédito 
                 ";
    //funcao construtor da classe
    function cl_numpref() {
        //classes dos rotulos dos campos
        $this->rotulo = new rotulo("numpref");
        $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
    }
    //funcao erro
    function erro($mostra,$retorna) {
        if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
            echo "<script>alert(\"".$this->erro_msg."\");</script>";
            if($retorna==true){
                echo "<script>location.href='".$this->pagina_retorno."'</script>";
            }
        }
    }
    // funcao para atualizar campos
    function atualizacampos($exclusao=false) {
        if($exclusao==false){
            $this->k03_anousu = ($this->k03_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["k03_anousu"]:$this->k03_anousu);
            $this->k03_instit = ($this->k03_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["k03_instit"]:$this->k03_instit);
            $this->k03_numpre = ($this->k03_numpre == ""?@$GLOBALS["HTTP_POST_VARS"]["k03_numpre"]:$this->k03_numpre);
            $this->k03_defope = ($this->k03_defope == ""?@$GLOBALS["HTTP_POST_VARS"]["k03_defope"]:$this->k03_defope);
            $this->k03_recjur = ($this->k03_recjur == ""?@$GLOBALS["HTTP_POST_VARS"]["k03_recjur"]:$this->k03_recjur);
            $this->k03_numsli = ($this->k03_numsli == ""?@$GLOBALS["HTTP_POST_VARS"]["k03_numsli"]:$this->k03_numsli);
            $this->k03_impend = ($this->k03_impend == "f"?@$GLOBALS["HTTP_POST_VARS"]["k03_impend"]:$this->k03_impend);
            $this->k03_unipri = ($this->k03_unipri == "f"?@$GLOBALS["HTTP_POST_VARS"]["k03_unipri"]:$this->k03_unipri);
            $this->k03_codbco = ($this->k03_codbco == ""?@$GLOBALS["HTTP_POST_VARS"]["k03_codbco"]:$this->k03_codbco);
            $this->k03_codage = ($this->k03_codage == ""?@$GLOBALS["HTTP_POST_VARS"]["k03_codage"]:$this->k03_codage);
            $this->k03_recmul = ($this->k03_recmul == ""?@$GLOBALS["HTTP_POST_VARS"]["k03_recmul"]:$this->k03_recmul);
            $this->k03_calrec = ($this->k03_calrec == "f"?@$GLOBALS["HTTP_POST_VARS"]["k03_calrec"]:$this->k03_calrec);
            $this->k03_msg = ($this->k03_msg == ""?@$GLOBALS["HTTP_POST_VARS"]["k03_msg"]:$this->k03_msg);
            $this->k03_msgcarne = ($this->k03_msgcarne == ""?@$GLOBALS["HTTP_POST_VARS"]["k03_msgcarne"]:$this->k03_msgcarne);
            $this->k03_msgbanco = ($this->k03_msgbanco == ""?@$GLOBALS["HTTP_POST_VARS"]["k03_msgbanco"]:$this->k03_msgbanco);
            $this->k03_certissvar = ($this->k03_certissvar == "f"?@$GLOBALS["HTTP_POST_VARS"]["k03_certissvar"]:$this->k03_certissvar);
            $this->k03_diasjust = ($this->k03_diasjust == ""?@$GLOBALS["HTTP_POST_VARS"]["k03_diasjust"]:$this->k03_diasjust);
            $this->k03_reccert = ($this->k03_reccert == "f"?@$GLOBALS["HTTP_POST_VARS"]["k03_reccert"]:$this->k03_reccert);
            $this->k03_taxagrupo = ($this->k03_taxagrupo == ""?@$GLOBALS["HTTP_POST_VARS"]["k03_taxagrupo"]:$this->k03_taxagrupo);
            $this->k03_tipocodcert = ($this->k03_tipocodcert == ""?@$GLOBALS["HTTP_POST_VARS"]["k03_tipocodcert"]:$this->k03_tipocodcert);
            $this->k03_reciboprot = ($this->k03_reciboprot == ""?@$GLOBALS["HTTP_POST_VARS"]["k03_reciboprot"]:$this->k03_reciboprot);
            $this->k03_regracnd = ($this->k03_regracnd == ""?@$GLOBALS["HTTP_POST_VARS"]["k03_regracnd"]:$this->k03_regracnd);
            $this->k03_reciboprotretencao = ($this->k03_reciboprotretencao == ""?@$GLOBALS["HTTP_POST_VARS"]["k03_reciboprotretencao"]:$this->k03_reciboprotretencao);
            $this->k03_tipocertidao = ($this->k03_tipocertidao == ""?@$GLOBALS["HTTP_POST_VARS"]["k03_tipocertidao"]:$this->k03_tipocertidao);
            $this->k03_separajurmulparc = ($this->k03_separajurmulparc == ""?@$GLOBALS["HTTP_POST_VARS"]["k03_separajurmulparc"]:$this->k03_separajurmulparc);
            $this->k03_respcgm = ($this->k03_respcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["k03_respcgm"]:$this->k03_respcgm);
            $this->k03_respcargo = ($this->k03_respcargo == ""?@$GLOBALS["HTTP_POST_VARS"]["k03_respcargo"]:$this->k03_respcargo);
            $this->k03_msgautent = ($this->k03_msgautent == ""?@$GLOBALS["HTTP_POST_VARS"]["k03_msgautent"]:$this->k03_msgautent);
            $this->k03_toleranciapgtoparc = ($this->k03_toleranciapgtoparc == ""?@$GLOBALS["HTTP_POST_VARS"]["k03_toleranciapgtoparc"]:$this->k03_toleranciapgtoparc);
            $this->k03_pgtoparcial = ($this->k03_pgtoparcial == "f"?@$GLOBALS["HTTP_POST_VARS"]["k03_pgtoparcial"]:$this->k03_pgtoparcial);
            $this->k03_reemissaorecibo = ($this->k03_reemissaorecibo == "f"?@$GLOBALS["HTTP_POST_VARS"]["k03_reemissaorecibo"]:$this->k03_reemissaorecibo);
            $this->k03_opcaoemissparcela = ($this->k03_opcaoemissparcela == ""?@$GLOBALS["HTTP_POST_VARS"]["k03_opcaoemissparcela"]:$this->k03_opcaoemissparcela);
            $this->k03_numprepgtoparcial = ($this->k03_numprepgtoparcial == ""?@$GLOBALS["HTTP_POST_VARS"]["k03_numprepgtoparcial"]:$this->k03_numprepgtoparcial);
            $this->k03_agrupadorarquivotxtbaixabanco = ($this->k03_agrupadorarquivotxtbaixabanco == ""?@$GLOBALS["HTTP_POST_VARS"]["k03_agrupadorarquivotxtbaixabanco"]:$this->k03_agrupadorarquivotxtbaixabanco);
            $this->k03_receitapadraocredito = ($this->k03_receitapadraocredito == ""?@$GLOBALS["HTTP_POST_VARS"]["k03_receitapadraocredito"]:$this->k03_receitapadraocredito);
            $this->k03_diasvalidadecertidao = ($this->k03_diasvalidadecertidao == ""?@$GLOBALS["HTTP_POST_VARS"]["k03_diasvalidadecertidao"]:$this->k03_diasvalidadecertidao);
            $this->k03_diasreemissaocertidao = ($this->k03_diasreemissaocertidao == ""?@$GLOBALS["HTTP_POST_VARS"]["k03_diasreemissaocertidao"]:$this->k03_diasreemissaocertidao);
            $this->k03_toleranciacredito = ($this->k03_toleranciacredito == ""?@$GLOBALS["HTTP_POST_VARS"]["k03_toleranciacredito"]:$this->k03_toleranciacredito);
        }else{
            $this->k03_anousu = ($this->k03_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["k03_anousu"]:$this->k03_anousu);
            $this->k03_instit = ($this->k03_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["k03_instit"]:$this->k03_instit);
        }
    }
    // funcao para inclusao
    function incluir ($k03_anousu,$k03_instit){
        $this->atualizacampos();
        if($this->k03_numpre == null ){
            $this->k03_numpre = "0";
        }
        if($this->k03_defope == null ){
            $this->erro_sql = " Campo Operação não informado.";
            $this->erro_campo = "k03_defope";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        if($this->k03_recjur == null ){
            $this->erro_sql = " Campo Receita Juros não informado.";
            $this->erro_campo = "k03_recjur";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        if($this->k03_numsli == null ){
            $this->erro_sql = " Campo Slip não informado.";
            $this->erro_campo = "k03_numsli";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        if($this->k03_impend == null ){
            $this->erro_sql = " Campo Imprime Endereço não informado.";
            $this->erro_campo = "k03_impend";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        if($this->k03_unipri == null ){
            $this->erro_sql = " Campo Única/Primeira não informado.";
            $this->erro_campo = "k03_unipri";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        if($this->k03_codbco == null ){
            $this->erro_sql = " Campo Banco não informado.";
            $this->erro_campo = "k03_codbco";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        if($this->k03_codage == null ){
            $this->erro_sql = " Campo Agência não informado.";
            $this->erro_campo = "k03_codage";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        if($this->k03_recmul == null ){
            $this->erro_sql = " Campo Receita Multa não informado.";
            $this->erro_campo = "k03_recmul";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        if($this->k03_calrec == null ){
            $this->erro_sql = " Campo Receita Cálculo não informado.";
            $this->erro_campo = "k03_calrec";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        if($this->k03_certissvar == null ){
            $this->erro_sql = " Campo Libera Variável não informado.";
            $this->erro_campo = "k03_certissvar";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        if($this->k03_diasjust == null ){
            $this->erro_sql = " Campo Dias Justif. não informado.";
            $this->erro_campo = "k03_diasjust";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        if($this->k03_reccert == null ){
            $this->erro_sql = " Campo Recibo na certidão não informado.";
            $this->erro_campo = "k03_reccert";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        if($this->k03_taxagrupo == null ){
            $this->erro_sql = " Campo Código do grupo de taxas não informado.";
            $this->erro_campo = "k03_taxagrupo";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        if($this->k03_tipocodcert == null ){
            $this->erro_sql = " Campo Tipo de Codificação não informado.";
            $this->erro_campo = "k03_tipocodcert";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        if($this->k03_reciboprot == null ){
            $this->erro_sql = " Campo Tipo do Recibo do Protocolo não informado.";
            $this->erro_campo = "k03_reciboprot";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        if($this->k03_regracnd == null ){
            $this->erro_sql = " Campo Regra paraEmissão CND não informado.";
            $this->erro_campo = "k03_regracnd";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        if($this->k03_reciboprotretencao == null ){
            $this->erro_sql = " Campo Tipo Recibo Retenção não informado.";
            $this->erro_campo = "k03_reciboprotretencao";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        if($this->k03_tipocertidao == null ){
            $this->erro_sql = " Campo Forma Emissão Certidão de Débitos não informado.";
            $this->erro_campo = "k03_tipocertidao";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        if($this->k03_separajurmulparc == null ){
            $this->erro_sql = " Campo Separar jur e mul no parcelamento não informado.";
            $this->erro_campo = "k03_separajurmulparc";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        if($this->k03_respcgm == null ){
            $this->k03_respcgm = "null";
        }
        if($this->k03_respcargo == null ){
            $this->k03_respcargo = "null";
        }
        if($this->k03_toleranciapgtoparc == null ){
            $this->erro_sql = " Campo Valor Tolerância Diferença Pagamento não informado.";
            $this->erro_campo = "k03_toleranciapgtoparc";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        if($this->k03_pgtoparcial == null ){
            $this->erro_sql = " Campo Ativa Pagamento Parcial não informado.";
            $this->erro_campo = "k03_pgtoparcial";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        if($this->k03_reemissaorecibo == null ){
            $this->erro_sql = " Campo Permite Reemissão de Recibo não informado.";
            $this->erro_campo = "k03_reemissaorecibo";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        if($this->k03_numprepgtoparcial == null ){
            $this->erro_sql = " Campo Numpre inicio pagamento parcial não informado.";
            $this->erro_campo = "k03_numprepgtoparcial";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        if($this->k03_agrupadorarquivotxtbaixabanco == null ){
            $this->erro_sql = " Campo Forma de Processamento de Arquivo TXT não informado.";
            $this->erro_campo = "k03_agrupadorarquivotxtbaixabanco";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        if($this->k03_diasvalidadecertidao == null ){
            $this->erro_sql = " Campo Dias para vencimento das Certidões não informado.";
            $this->erro_campo = "k03_diasvalidadecertidao";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        if($this->k03_diasreemissaocertidao == null ){
            $this->erro_sql = " Campo Dias para reemissão das Certidões não informado.";
            $this->erro_campo = "k03_diasreemissaocertidao";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }

        if($this->k03_toleranciacredito == null ){
            $this->erro_sql = " Campo Tolerãncia para Crédito não informado.";
            $this->erro_campo = "k03_toleranciacredito";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->k03_receitapadraocredito == null) {
            $this->k03_receitapadraocredito = 'null';
        }

        if($k03_numpre == "" || $k03_numpre == null ){
            $result = db_query("select nextval('numpref_k03_numpre_seq')");
            if($result==false){
                $this->erro_banco = str_replace("\n","",@pg_last_error());
                $this->erro_sql   = "Verifique o cadastro da sequencia: numpref_k03_numpre_seq do campo: k03_numpre";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            }
            $this->k03_numpre = pg_result($result,0,0);
        }else{
            $result = db_query("select last_value from numpref_k03_numpre_seq");
            if(($result != false) && (pg_result($result,0,0) < $k03_numpre)){
                $this->erro_sql = " Campo k03_numpre maior que último número da sequencia.";
                $this->erro_banco = "Sequencia menor que este número.";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            }else{
                $this->k03_numpre = $k03_numpre;
            }
        }
        if(($this->k03_anousu == null) || ($this->k03_anousu == "") ){
            $this->erro_sql = " Campo k03_anousu nao declarado.";
            $this->erro_banco = "Chave Primaria zerada.";
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        if(($this->k03_instit == null) || ($this->k03_instit == "") ){
            $this->erro_sql = " Campo k03_instit nao declarado.";
            $this->erro_banco = "Chave Primaria zerada.";
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        $sql = "insert into numpref(
                                       k03_anousu 
                                      ,k03_instit 
                                      ,k03_numpre 
                                      ,k03_defope 
                                      ,k03_recjur 
                                      ,k03_numsli 
                                      ,k03_impend 
                                      ,k03_unipri 
                                      ,k03_codbco 
                                      ,k03_codage 
                                      ,k03_recmul 
                                      ,k03_calrec 
                                      ,k03_msg 
                                      ,k03_msgcarne 
                                      ,k03_msgbanco 
                                      ,k03_certissvar 
                                      ,k03_diasjust 
                                      ,k03_reccert 
                                      ,k03_taxagrupo 
                                      ,k03_tipocodcert 
                                      ,k03_reciboprot 
                                      ,k03_regracnd 
                                      ,k03_reciboprotretencao 
                                      ,k03_tipocertidao 
                                      ,k03_separajurmulparc 
                                      ,k03_respcgm 
                                      ,k03_respcargo 
                                      ,k03_msgautent 
                                      ,k03_toleranciapgtoparc 
                                      ,k03_pgtoparcial 
                                      ,k03_reemissaorecibo 
                                      ,k03_opcaoemissparcela 
                                      ,k03_numprepgtoparcial 
                                      ,k03_agrupadorarquivotxtbaixabanco 
                                      ,k03_receitapadraocredito 
                                      ,k03_diasvalidadecertidao 
                                      ,k03_diasreemissaocertidao 
                                      ,k03_toleranciacredito 
                       )
                values (
                                $this->k03_anousu 
                               ,$this->k03_instit 
                               ,$this->k03_numpre 
                               ,$this->k03_defope 
                               ,$this->k03_recjur 
                               ,$this->k03_numsli 
                               ,'$this->k03_impend' 
                               ,'$this->k03_unipri' 
                               ,$this->k03_codbco 
                               ,'$this->k03_codage' 
                               ,$this->k03_recmul 
                               ,'$this->k03_calrec' 
                               ,'$this->k03_msg' 
                               ,'$this->k03_msgcarne' 
                               ,'$this->k03_msgbanco' 
                               ,'$this->k03_certissvar' 
                               ,$this->k03_diasjust 
                               ,'$this->k03_reccert' 
                               ,$this->k03_taxagrupo 
                               ,$this->k03_tipocodcert 
                               ,$this->k03_reciboprot 
                               ,$this->k03_regracnd 
                               ,$this->k03_reciboprotretencao 
                               ,$this->k03_tipocertidao 
                               ,$this->k03_separajurmulparc 
                               ,$this->k03_respcgm 
                               ,$this->k03_respcargo 
                               ,'$this->k03_msgautent' 
                               ,$this->k03_toleranciapgtoparc 
                               ,'$this->k03_pgtoparcial' 
                               ,'$this->k03_reemissaorecibo' 
                               ,'$this->k03_opcaoemissparcela' 
                               ,$this->k03_numprepgtoparcial 
                               ,$this->k03_agrupadorarquivotxtbaixabanco 
                               ,$this->k03_receitapadraocredito 
                               ,$this->k03_diasvalidadecertidao 
                               ,$this->k03_diasreemissaocertidao 
                               ,$this->k03_toleranciacredito 
                      )";
        $result = db_query($sql);
        if($result==false){
            $this->erro_banco = str_replace("\n","",@pg_last_error());
            if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
                $this->erro_sql   = "Numerações ($this->k03_anousu."-".$this->k03_instit) nao Incluído. Inclusao Abortada.";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_banco = "Numerações já Cadastrado";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            }else{
                $this->erro_sql   = "Numerações ($this->k03_anousu."-".$this->k03_instit) nao Incluído. Inclusao Abortada.";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            }
            $this->erro_status = "0";
            $this->numrows_incluir= 0;
            return false;
        }
        $this->erro_banco = "";
        $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : ".$this->k03_anousu."-".$this->k03_instit;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_incluir= pg_affected_rows($result);
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            $resaco = $this->sql_record($this->sql_query_file($this->k03_anousu,$this->k03_instit  ));
            if(($resaco!=false)||($this->numrows!=0)){

                $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                $acount = pg_result($resac,0,0);
                $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
                $resac = db_query("insert into db_acountkey values($acount,1904,'$this->k03_anousu','I')");
                $resac = db_query("insert into db_acountkey values($acount,10716,'$this->k03_instit','I')");
                $resac = db_query("insert into db_acount values($acount,318,1904,'','".AddSlashes(pg_result($resaco,0,'k03_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,318,10716,'','".AddSlashes(pg_result($resaco,0,'k03_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,318,1905,'','".AddSlashes(pg_result($resaco,0,'k03_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,318,1906,'','".AddSlashes(pg_result($resaco,0,'k03_defope'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,318,1907,'','".AddSlashes(pg_result($resaco,0,'k03_recjur'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,318,1908,'','".AddSlashes(pg_result($resaco,0,'k03_numsli'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,318,1909,'','".AddSlashes(pg_result($resaco,0,'k03_impend'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,318,1910,'','".AddSlashes(pg_result($resaco,0,'k03_unipri'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,318,1911,'','".AddSlashes(pg_result($resaco,0,'k03_codbco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,318,1912,'','".AddSlashes(pg_result($resaco,0,'k03_codage'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,318,1913,'','".AddSlashes(pg_result($resaco,0,'k03_recmul'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,318,1914,'','".AddSlashes(pg_result($resaco,0,'k03_calrec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,318,1915,'','".AddSlashes(pg_result($resaco,0,'k03_msg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,318,7918,'','".AddSlashes(pg_result($resaco,0,'k03_msgcarne'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,318,7925,'','".AddSlashes(pg_result($resaco,0,'k03_msgbanco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,318,7943,'','".AddSlashes(pg_result($resaco,0,'k03_certissvar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,318,8737,'','".AddSlashes(pg_result($resaco,0,'k03_diasjust'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,318,8797,'','".AddSlashes(pg_result($resaco,0,'k03_reccert'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,318,8799,'','".AddSlashes(pg_result($resaco,0,'k03_taxagrupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,318,9419,'','".AddSlashes(pg_result($resaco,0,'k03_tipocodcert'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,318,11859,'','".AddSlashes(pg_result($resaco,0,'k03_reciboprot'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,318,14400,'','".AddSlashes(pg_result($resaco,0,'k03_regracnd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,318,14484,'','".AddSlashes(pg_result($resaco,0,'k03_reciboprotretencao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,318,14587,'','".AddSlashes(pg_result($resaco,0,'k03_tipocertidao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,318,15036,'','".AddSlashes(pg_result($resaco,0,'k03_separajurmulparc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,318,17195,'','".AddSlashes(pg_result($resaco,0,'k03_respcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,318,17196,'','".AddSlashes(pg_result($resaco,0,'k03_respcargo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,318,17943,'','".AddSlashes(pg_result($resaco,0,'k03_msgautent'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,318,18059,'','".AddSlashes(pg_result($resaco,0,'k03_toleranciapgtoparc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,318,18150,'','".AddSlashes(pg_result($resaco,0,'k03_pgtoparcial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,318,18429,'','".AddSlashes(pg_result($resaco,0,'k03_reemissaorecibo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,318,18468,'','".AddSlashes(pg_result($resaco,0,'k03_opcaoemissparcela'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,318,18874,'','".AddSlashes(pg_result($resaco,0,'k03_numprepgtoparcial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,318,19223,'','".AddSlashes(pg_result($resaco,0,'k03_agrupadorarquivotxtbaixabanco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,318,19647,'','".AddSlashes(pg_result($resaco,0,'k03_receitapadraocredito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,318,20229,'','".AddSlashes(pg_result($resaco,0,'k03_diasvalidadecertidao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,318,20230,'','".AddSlashes(pg_result($resaco,0,'k03_diasreemissaocertidao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,318,20614,'','".AddSlashes(pg_result($resaco,0,'k03_toleranciacredito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
            }
        }
        return true;
    }
    // funcao para alteracao
    function alterar ($k03_anousu=null,$k03_instit=null) {
        $this->atualizacampos();
        $sql = " update numpref set ";
        $virgula = "";
        if(trim($this->k03_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k03_anousu"])){
            $sql  .= $virgula." k03_anousu = $this->k03_anousu ";
            $virgula = ",";
            if(trim($this->k03_anousu) == null ){
                $this->erro_sql = " Campo Exercício não informado.";
                $this->erro_campo = "k03_anousu";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if(trim($this->k03_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k03_instit"])){
            $sql  .= $virgula." k03_instit = $this->k03_instit ";
            $virgula = ",";
            if(trim($this->k03_instit) == null ){
                $this->erro_sql = " Campo Cód. Instituição não informado.";
                $this->erro_campo = "k03_instit";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if(trim($this->k03_numpre)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k03_numpre"])){
            if(trim($this->k03_numpre)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k03_numpre"])){
                $this->k03_numpre = "0" ;
            }
            $sql  .= $virgula." k03_numpre = $this->k03_numpre ";
            $virgula = ",";
        }
        if(trim($this->k03_defope)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k03_defope"])){
            $sql  .= $virgula." k03_defope = $this->k03_defope ";
            $virgula = ",";
            if(trim($this->k03_defope) == null ){
                $this->erro_sql = " Campo Operação não informado.";
                $this->erro_campo = "k03_defope";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if(trim($this->k03_recjur)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k03_recjur"])){
            $sql  .= $virgula." k03_recjur = $this->k03_recjur ";
            $virgula = ",";
            if(trim($this->k03_recjur) == null ){
                $this->erro_sql = " Campo Receita Juros não informado.";
                $this->erro_campo = "k03_recjur";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if(trim($this->k03_numsli)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k03_numsli"])){
            $sql  .= $virgula." k03_numsli = $this->k03_numsli ";
            $virgula = ",";
            if(trim($this->k03_numsli) == null ){
                $this->erro_sql = " Campo Slip não informado.";
                $this->erro_campo = "k03_numsli";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if(trim($this->k03_impend)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k03_impend"])){
            $sql  .= $virgula." k03_impend = '$this->k03_impend' ";
            $virgula = ",";
            if(trim($this->k03_impend) == null ){
                $this->erro_sql = " Campo Imprime Endereço não informado.";
                $this->erro_campo = "k03_impend";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if(trim($this->k03_unipri)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k03_unipri"])){
            $sql  .= $virgula." k03_unipri = '$this->k03_unipri' ";
            $virgula = ",";
            if(trim($this->k03_unipri) == null ){
                $this->erro_sql = " Campo Única/Primeira não informado.";
                $this->erro_campo = "k03_unipri";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if(trim($this->k03_codbco)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k03_codbco"])){
            $sql  .= $virgula." k03_codbco = $this->k03_codbco ";
            $virgula = ",";
            if(trim($this->k03_codbco) == null ){
                $this->erro_sql = " Campo Banco não informado.";
                $this->erro_campo = "k03_codbco";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if(trim($this->k03_codage)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k03_codage"])){
            $sql  .= $virgula." k03_codage = '$this->k03_codage' ";
            $virgula = ",";
            if(trim($this->k03_codage) == null ){
                $this->erro_sql = " Campo Agência não informado.";
                $this->erro_campo = "k03_codage";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if(trim($this->k03_recmul)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k03_recmul"])){
            $sql  .= $virgula." k03_recmul = $this->k03_recmul ";
            $virgula = ",";
            if(trim($this->k03_recmul) == null ){
                $this->erro_sql = " Campo Receita Multa não informado.";
                $this->erro_campo = "k03_recmul";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if(trim($this->k03_calrec)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k03_calrec"])){
            $sql  .= $virgula." k03_calrec = '$this->k03_calrec' ";
            $virgula = ",";
            if(trim($this->k03_calrec) == null ){
                $this->erro_sql = " Campo Receita Cálculo não informado.";
                $this->erro_campo = "k03_calrec";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if(trim($this->k03_msg)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k03_msg"])){
            $sql  .= $virgula." k03_msg = '$this->k03_msg' ";
            $virgula = ",";
        }
        if(trim($this->k03_msgcarne)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k03_msgcarne"])){
            $sql  .= $virgula." k03_msgcarne = '$this->k03_msgcarne' ";
            $virgula = ",";
        }
        if(trim($this->k03_msgbanco)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k03_msgbanco"])){
            $sql  .= $virgula." k03_msgbanco = '$this->k03_msgbanco' ";
            $virgula = ",";
        }
        if(trim($this->k03_certissvar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k03_certissvar"])){
            $sql  .= $virgula." k03_certissvar = '$this->k03_certissvar' ";
            $virgula = ",";
            if(trim($this->k03_certissvar) == null ){
                $this->erro_sql = " Campo Libera Variável não informado.";
                $this->erro_campo = "k03_certissvar";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if(trim($this->k03_diasjust)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k03_diasjust"])){
            $sql  .= $virgula." k03_diasjust = $this->k03_diasjust ";
            $virgula = ",";
            if(trim($this->k03_diasjust) == null ){
                $this->erro_sql = " Campo Dias Justif. não informado.";
                $this->erro_campo = "k03_diasjust";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if(trim($this->k03_reccert)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k03_reccert"])){
            $sql  .= $virgula." k03_reccert = '$this->k03_reccert' ";
            $virgula = ",";
            if(trim($this->k03_reccert) == null ){
                $this->erro_sql = " Campo Recibo na certidão não informado.";
                $this->erro_campo = "k03_reccert";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if(trim($this->k03_taxagrupo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k03_taxagrupo"])){
            $sql  .= $virgula." k03_taxagrupo = $this->k03_taxagrupo ";
            $virgula = ",";
            if(trim($this->k03_taxagrupo) == null ){
                $this->erro_sql = " Campo Código do grupo de taxas não informado.";
                $this->erro_campo = "k03_taxagrupo";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if(trim($this->k03_tipocodcert)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k03_tipocodcert"])){
            $sql  .= $virgula." k03_tipocodcert = $this->k03_tipocodcert ";
            $virgula = ",";
            if(trim($this->k03_tipocodcert) == null ){
                $this->erro_sql = " Campo Tipo de Codificação não informado.";
                $this->erro_campo = "k03_tipocodcert";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if(trim($this->k03_reciboprot)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k03_reciboprot"])){
            $sql  .= $virgula." k03_reciboprot = $this->k03_reciboprot ";
            $virgula = ",";
            if(trim($this->k03_reciboprot) == null ){
                $this->erro_sql = " Campo Tipo do Recibo do Protocolo não informado.";
                $this->erro_campo = "k03_reciboprot";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if(trim($this->k03_regracnd)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k03_regracnd"])){
            $sql  .= $virgula." k03_regracnd = $this->k03_regracnd ";
            $virgula = ",";
            if(trim($this->k03_regracnd) == null ){
                $this->erro_sql = " Campo Regra paraEmissão CND não informado.";
                $this->erro_campo = "k03_regracnd";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if(trim($this->k03_reciboprotretencao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k03_reciboprotretencao"])){
            $sql  .= $virgula." k03_reciboprotretencao = $this->k03_reciboprotretencao ";
            $virgula = ",";
            if(trim($this->k03_reciboprotretencao) == null ){
                $this->erro_sql = " Campo Tipo Recibo Retenção não informado.";
                $this->erro_campo = "k03_reciboprotretencao";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if(trim($this->k03_tipocertidao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k03_tipocertidao"])){
            $sql  .= $virgula." k03_tipocertidao = $this->k03_tipocertidao ";
            $virgula = ",";
            if(trim($this->k03_tipocertidao) == null ){
                $this->erro_sql = " Campo Forma Emissão Certidão de Débitos não informado.";
                $this->erro_campo = "k03_tipocertidao";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if(trim($this->k03_separajurmulparc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k03_separajurmulparc"])){
            $sql  .= $virgula." k03_separajurmulparc = '$this->k03_separajurmulparc' ";
            $virgula = ",";
            if(trim($this->k03_separajurmulparc) == null ){
                $this->erro_sql = " Campo Separar jur e mul no parcelamento não informado.";
                $this->erro_campo = "k03_separajurmulparc";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if(trim($this->k03_respcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k03_respcgm"])){
            if(trim($this->k03_respcgm)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k03_respcgm"])){
                $this->k03_respcgm = "null" ;
            }
            $sql  .= $virgula." k03_respcgm = $this->k03_respcgm ";
            $virgula = ",";
        }
        if(trim($this->k03_respcargo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k03_respcargo"])){
            if(trim($this->k03_respcargo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k03_respcargo"])){
                $this->k03_respcargo = "null" ;
            }
            $sql  .= $virgula." k03_respcargo = $this->k03_respcargo ";
            $virgula = ",";
        }
        if(trim($this->k03_msgautent)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k03_msgautent"])){
            $sql  .= $virgula." k03_msgautent = '$this->k03_msgautent' ";
            $virgula = ",";
        }
        if(trim($this->k03_toleranciapgtoparc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k03_toleranciapgtoparc"])){
            $sql  .= $virgula." k03_toleranciapgtoparc = $this->k03_toleranciapgtoparc ";
            $virgula = ",";
            if(trim($this->k03_toleranciapgtoparc) == null ){
                $this->erro_sql = " Campo Valor Tolerância Diferença Pagamento não informado.";
                $this->erro_campo = "k03_toleranciapgtoparc";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if(trim($this->k03_pgtoparcial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k03_pgtoparcial"])){
            $sql  .= $virgula." k03_pgtoparcial = '$this->k03_pgtoparcial' ";
            $virgula = ",";
            if(trim($this->k03_pgtoparcial) == null ){
                $this->erro_sql = " Campo Ativa Pagamento Parcial não informado.";
                $this->erro_campo = "k03_pgtoparcial";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if(trim($this->k03_reemissaorecibo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k03_reemissaorecibo"])){
            $sql  .= $virgula." k03_reemissaorecibo = '$this->k03_reemissaorecibo' ";
            $virgula = ",";
            if(trim($this->k03_reemissaorecibo) == null ){
                $this->erro_sql = " Campo Permite Reemissão de Recibo não informado.";
                $this->erro_campo = "k03_reemissaorecibo";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if(trim($this->k03_opcaoemissparcela)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k03_opcaoemissparcela"])){
            $sql  .= $virgula." k03_opcaoemissparcela = '$this->k03_opcaoemissparcela' ";
            $virgula = ",";
        }
        if(trim($this->k03_numprepgtoparcial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k03_numprepgtoparcial"])){
            $sql  .= $virgula." k03_numprepgtoparcial = $this->k03_numprepgtoparcial ";
            $virgula = ",";
            if(trim($this->k03_numprepgtoparcial) == null ){
                $this->erro_sql = " Campo Numpre inicio pagamento parcial não informado.";
                $this->erro_campo = "k03_numprepgtoparcial";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if(trim($this->k03_agrupadorarquivotxtbaixabanco)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k03_agrupadorarquivotxtbaixabanco"])){
            $sql  .= $virgula." k03_agrupadorarquivotxtbaixabanco = $this->k03_agrupadorarquivotxtbaixabanco ";
            $virgula = ",";
            if(trim($this->k03_agrupadorarquivotxtbaixabanco) == null ){
                $this->erro_sql = " Campo Forma de Processamento de Arquivo TXT não informado.";
                $this->erro_campo = "k03_agrupadorarquivotxtbaixabanco";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if(trim($this->k03_receitapadraocredito)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k03_receitapadraocredito"])){
            if(trim($this->k03_receitapadraocredito)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k03_receitapadraocredito"])){
                $this->k03_receitapadraocredito = "null" ;
            }
            $sql  .= $virgula." k03_receitapadraocredito = $this->k03_receitapadraocredito ";
            $virgula = ",";
        }
        if(trim($this->k03_diasvalidadecertidao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k03_diasvalidadecertidao"])){
            $sql  .= $virgula." k03_diasvalidadecertidao = $this->k03_diasvalidadecertidao ";
            $virgula = ",";
            if(trim($this->k03_diasvalidadecertidao) == null ){
                $this->erro_sql = " Campo Dias para vencimento das Certidões não informado.";
                $this->erro_campo = "k03_diasvalidadecertidao";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            }
            if( !DBNumber::isInteger( trim($this->k03_diasvalidadecertidao) ) ){
                $this->k03_diasreemissaocertidao = '';
                $this->erro_sql = " Campo Dias para vencimento das Certidões deve ser preenchido somente com números!";
                $this->erro_campo = "k03_diasvalidadecertidao";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if(trim($this->k03_diasreemissaocertidao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k03_diasreemissaocertidao"])){
            $sql  .= $virgula." k03_diasreemissaocertidao = $this->k03_diasreemissaocertidao ";
            $virgula = ",";
            if(trim($this->k03_diasreemissaocertidao) == null ){
                $this->erro_sql = " Campo Dias para reemissão das Certidões não informado.";
                $this->erro_campo = "k03_diasvalidadecertidao";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            }

            if( !DBNumber::isInteger( trim($this->k03_diasreemissaocertidao) ) ){
                $this->k03_diasreemissaocertidao = '';
                $this->erro_sql = " Campo Dias para reemissão das Certidões deve ser preenchido somente com números!";
                $this->erro_campo = "k03_diasvalidadecertidao";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            }
        }

        if(trim($this->k03_toleranciacredito)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k03_toleranciacredito"])){
            $sql  .= $virgula." k03_toleranciacredito = $this->k03_toleranciacredito ";
            $virgula = ",";
            if(trim($this->k03_toleranciacredito) == null ){
                $this->erro_sql = " Campo Tolerãncia para Crédito não informado.";
                $this->erro_campo = "k03_toleranciacredito";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        $sql .= " where ";
        if($k03_anousu!=null){
            $sql .= " k03_anousu = $this->k03_anousu";
        }
        if($k03_instit!=null){
            $sql .= " and  k03_instit = $this->k03_instit";
        }
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            $resaco = $this->sql_record($this->sql_query_file($this->k03_anousu,$this->k03_instit));
            if($this->numrows>0){

                for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

                    $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac,0,0);
                    $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
                    $resac = db_query("insert into db_acountkey values($acount,1904,'$this->k03_anousu','A')");
                    $resac = db_query("insert into db_acountkey values($acount,10716,'$this->k03_instit','A')");
                    if(isset($GLOBALS["HTTP_POST_VARS"]["k03_anousu"]) || $this->k03_anousu != "")
                        $resac = db_query("insert into db_acount values($acount,318,1904,'".AddSlashes(pg_result($resaco,$conresaco,'k03_anousu'))."','$this->k03_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    if(isset($GLOBALS["HTTP_POST_VARS"]["k03_instit"]) || $this->k03_instit != "")
                        $resac = db_query("insert into db_acount values($acount,318,10716,'".AddSlashes(pg_result($resaco,$conresaco,'k03_instit'))."','$this->k03_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    if(isset($GLOBALS["HTTP_POST_VARS"]["k03_numpre"]) || $this->k03_numpre != "")
                        $resac = db_query("insert into db_acount values($acount,318,1905,'".AddSlashes(pg_result($resaco,$conresaco,'k03_numpre'))."','$this->k03_numpre',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    if(isset($GLOBALS["HTTP_POST_VARS"]["k03_defope"]) || $this->k03_defope != "")
                        $resac = db_query("insert into db_acount values($acount,318,1906,'".AddSlashes(pg_result($resaco,$conresaco,'k03_defope'))."','$this->k03_defope',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    if(isset($GLOBALS["HTTP_POST_VARS"]["k03_recjur"]) || $this->k03_recjur != "")
                        $resac = db_query("insert into db_acount values($acount,318,1907,'".AddSlashes(pg_result($resaco,$conresaco,'k03_recjur'))."','$this->k03_recjur',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    if(isset($GLOBALS["HTTP_POST_VARS"]["k03_numsli"]) || $this->k03_numsli != "")
                        $resac = db_query("insert into db_acount values($acount,318,1908,'".AddSlashes(pg_result($resaco,$conresaco,'k03_numsli'))."','$this->k03_numsli',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    if(isset($GLOBALS["HTTP_POST_VARS"]["k03_impend"]) || $this->k03_impend != "")
                        $resac = db_query("insert into db_acount values($acount,318,1909,'".AddSlashes(pg_result($resaco,$conresaco,'k03_impend'))."','$this->k03_impend',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    if(isset($GLOBALS["HTTP_POST_VARS"]["k03_unipri"]) || $this->k03_unipri != "")
                        $resac = db_query("insert into db_acount values($acount,318,1910,'".AddSlashes(pg_result($resaco,$conresaco,'k03_unipri'))."','$this->k03_unipri',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    if(isset($GLOBALS["HTTP_POST_VARS"]["k03_codbco"]) || $this->k03_codbco != "")
                        $resac = db_query("insert into db_acount values($acount,318,1911,'".AddSlashes(pg_result($resaco,$conresaco,'k03_codbco'))."','$this->k03_codbco',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    if(isset($GLOBALS["HTTP_POST_VARS"]["k03_codage"]) || $this->k03_codage != "")
                        $resac = db_query("insert into db_acount values($acount,318,1912,'".AddSlashes(pg_result($resaco,$conresaco,'k03_codage'))."','$this->k03_codage',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    if(isset($GLOBALS["HTTP_POST_VARS"]["k03_recmul"]) || $this->k03_recmul != "")
                        $resac = db_query("insert into db_acount values($acount,318,1913,'".AddSlashes(pg_result($resaco,$conresaco,'k03_recmul'))."','$this->k03_recmul',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    if(isset($GLOBALS["HTTP_POST_VARS"]["k03_calrec"]) || $this->k03_calrec != "")
                        $resac = db_query("insert into db_acount values($acount,318,1914,'".AddSlashes(pg_result($resaco,$conresaco,'k03_calrec'))."','$this->k03_calrec',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    if(isset($GLOBALS["HTTP_POST_VARS"]["k03_msg"]) || $this->k03_msg != "")
                        $resac = db_query("insert into db_acount values($acount,318,1915,'".AddSlashes(pg_result($resaco,$conresaco,'k03_msg'))."','$this->k03_msg',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    if(isset($GLOBALS["HTTP_POST_VARS"]["k03_msgcarne"]) || $this->k03_msgcarne != "")
                        $resac = db_query("insert into db_acount values($acount,318,7918,'".AddSlashes(pg_result($resaco,$conresaco,'k03_msgcarne'))."','$this->k03_msgcarne',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    if(isset($GLOBALS["HTTP_POST_VARS"]["k03_msgbanco"]) || $this->k03_msgbanco != "")
                        $resac = db_query("insert into db_acount values($acount,318,7925,'".AddSlashes(pg_result($resaco,$conresaco,'k03_msgbanco'))."','$this->k03_msgbanco',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    if(isset($GLOBALS["HTTP_POST_VARS"]["k03_certissvar"]) || $this->k03_certissvar != "")
                        $resac = db_query("insert into db_acount values($acount,318,7943,'".AddSlashes(pg_result($resaco,$conresaco,'k03_certissvar'))."','$this->k03_certissvar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    if(isset($GLOBALS["HTTP_POST_VARS"]["k03_diasjust"]) || $this->k03_diasjust != "")
                        $resac = db_query("insert into db_acount values($acount,318,8737,'".AddSlashes(pg_result($resaco,$conresaco,'k03_diasjust'))."','$this->k03_diasjust',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    if(isset($GLOBALS["HTTP_POST_VARS"]["k03_reccert"]) || $this->k03_reccert != "")
                        $resac = db_query("insert into db_acount values($acount,318,8797,'".AddSlashes(pg_result($resaco,$conresaco,'k03_reccert'))."','$this->k03_reccert',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    if(isset($GLOBALS["HTTP_POST_VARS"]["k03_taxagrupo"]) || $this->k03_taxagrupo != "")
                        $resac = db_query("insert into db_acount values($acount,318,8799,'".AddSlashes(pg_result($resaco,$conresaco,'k03_taxagrupo'))."','$this->k03_taxagrupo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    if(isset($GLOBALS["HTTP_POST_VARS"]["k03_tipocodcert"]) || $this->k03_tipocodcert != "")
                        $resac = db_query("insert into db_acount values($acount,318,9419,'".AddSlashes(pg_result($resaco,$conresaco,'k03_tipocodcert'))."','$this->k03_tipocodcert',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    if(isset($GLOBALS["HTTP_POST_VARS"]["k03_reciboprot"]) || $this->k03_reciboprot != "")
                        $resac = db_query("insert into db_acount values($acount,318,11859,'".AddSlashes(pg_result($resaco,$conresaco,'k03_reciboprot'))."','$this->k03_reciboprot',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    if(isset($GLOBALS["HTTP_POST_VARS"]["k03_regracnd"]) || $this->k03_regracnd != "")
                        $resac = db_query("insert into db_acount values($acount,318,14400,'".AddSlashes(pg_result($resaco,$conresaco,'k03_regracnd'))."','$this->k03_regracnd',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    if(isset($GLOBALS["HTTP_POST_VARS"]["k03_reciboprotretencao"]) || $this->k03_reciboprotretencao != "")
                        $resac = db_query("insert into db_acount values($acount,318,14484,'".AddSlashes(pg_result($resaco,$conresaco,'k03_reciboprotretencao'))."','$this->k03_reciboprotretencao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    if(isset($GLOBALS["HTTP_POST_VARS"]["k03_tipocertidao"]) || $this->k03_tipocertidao != "")
                        $resac = db_query("insert into db_acount values($acount,318,14587,'".AddSlashes(pg_result($resaco,$conresaco,'k03_tipocertidao'))."','$this->k03_tipocertidao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    if(isset($GLOBALS["HTTP_POST_VARS"]["k03_separajurmulparc"]) || $this->k03_separajurmulparc != "")
                        $resac = db_query("insert into db_acount values($acount,318,15036,'".AddSlashes(pg_result($resaco,$conresaco,'k03_separajurmulparc'))."','$this->k03_separajurmulparc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    if(isset($GLOBALS["HTTP_POST_VARS"]["k03_respcgm"]) || $this->k03_respcgm != "")
                        $resac = db_query("insert into db_acount values($acount,318,17195,'".AddSlashes(pg_result($resaco,$conresaco,'k03_respcgm'))."','$this->k03_respcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    if(isset($GLOBALS["HTTP_POST_VARS"]["k03_respcargo"]) || $this->k03_respcargo != "")
                        $resac = db_query("insert into db_acount values($acount,318,17196,'".AddSlashes(pg_result($resaco,$conresaco,'k03_respcargo'))."','$this->k03_respcargo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    if(isset($GLOBALS["HTTP_POST_VARS"]["k03_msgautent"]) || $this->k03_msgautent != "")
                        $resac = db_query("insert into db_acount values($acount,318,17943,'".AddSlashes(pg_result($resaco,$conresaco,'k03_msgautent'))."','$this->k03_msgautent',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    if(isset($GLOBALS["HTTP_POST_VARS"]["k03_toleranciapgtoparc"]) || $this->k03_toleranciapgtoparc != "")
                        $resac = db_query("insert into db_acount values($acount,318,18059,'".AddSlashes(pg_result($resaco,$conresaco,'k03_toleranciapgtoparc'))."','$this->k03_toleranciapgtoparc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    if(isset($GLOBALS["HTTP_POST_VARS"]["k03_pgtoparcial"]) || $this->k03_pgtoparcial != "")
                        $resac = db_query("insert into db_acount values($acount,318,18150,'".AddSlashes(pg_result($resaco,$conresaco,'k03_pgtoparcial'))."','$this->k03_pgtoparcial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    if(isset($GLOBALS["HTTP_POST_VARS"]["k03_reemissaorecibo"]) || $this->k03_reemissaorecibo != "")
                        $resac = db_query("insert into db_acount values($acount,318,18429,'".AddSlashes(pg_result($resaco,$conresaco,'k03_reemissaorecibo'))."','$this->k03_reemissaorecibo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    if(isset($GLOBALS["HTTP_POST_VARS"]["k03_opcaoemissparcela"]) || $this->k03_opcaoemissparcela != "")
                        $resac = db_query("insert into db_acount values($acount,318,18468,'".AddSlashes(pg_result($resaco,$conresaco,'k03_opcaoemissparcela'))."','$this->k03_opcaoemissparcela',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    if(isset($GLOBALS["HTTP_POST_VARS"]["k03_numprepgtoparcial"]) || $this->k03_numprepgtoparcial != "")
                        $resac = db_query("insert into db_acount values($acount,318,18874,'".AddSlashes(pg_result($resaco,$conresaco,'k03_numprepgtoparcial'))."','$this->k03_numprepgtoparcial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    if(isset($GLOBALS["HTTP_POST_VARS"]["k03_agrupadorarquivotxtbaixabanco"]) || $this->k03_agrupadorarquivotxtbaixabanco != "")
                        $resac = db_query("insert into db_acount values($acount,318,19223,'".AddSlashes(pg_result($resaco,$conresaco,'k03_agrupadorarquivotxtbaixabanco'))."','$this->k03_agrupadorarquivotxtbaixabanco',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    if(isset($GLOBALS["HTTP_POST_VARS"]["k03_receitapadraocredito"]) || $this->k03_receitapadraocredito != "")
                        $resac = db_query("insert into db_acount values($acount,318,19647,'".AddSlashes(pg_result($resaco,$conresaco,'k03_receitapadraocredito'))."','$this->k03_receitapadraocredito',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    if(isset($GLOBALS["HTTP_POST_VARS"]["k03_diasvalidadecertidao"]) || $this->k03_diasvalidadecertidao != "")
                        $resac = db_query("insert into db_acount values($acount,318,20229,'".AddSlashes(pg_result($resaco,$conresaco,'k03_diasvalidadecertidao'))."','$this->k03_diasvalidadecertidao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    if(isset($GLOBALS["HTTP_POST_VARS"]["k03_diasreemissaocertidao"]) || $this->k03_diasreemissaocertidao != "")
                        $resac = db_query("insert into db_acount values($acount,318,20230,'".AddSlashes(pg_result($resaco,$conresaco,'k03_diasreemissaocertidao'))."','$this->k03_diasreemissaocertidao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    if(isset($GLOBALS["HTTP_POST_VARS"]["k03_toleranciacredito"]) || $this->k03_toleranciacredito != "")
                        $resac = db_query("insert into db_acount values($acount,318,20614,'".AddSlashes(pg_result($resaco,$conresaco,'k03_toleranciacredito'))."','$this->k03_toleranciacredito',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                }
            }
        }
        $result = db_query($sql);
        if($result==false){
            $this->erro_banco = str_replace("\n","",@pg_last_error());
            $this->erro_sql   = "Numerações nao Alterado. Alteracao Abortada.\\n";
            $this->erro_sql .= "Valores : ".$this->k03_anousu."-".$this->k03_instit;
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            $this->numrows_alterar = 0;
            return false;
        }else{
            if(pg_affected_rows($result)==0){
                $this->erro_banco = "";
                $this->erro_sql = "Numerações nao foi Alterado. Alteracao Executada.\\n";
                $this->erro_sql .= "Valores : ".$this->k03_anousu."-".$this->k03_instit;
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = 0;
                return true;
            }else{
                $this->erro_banco = "";
                $this->erro_sql = "Alteração efetuada com Sucesso\\n";
                $this->erro_sql .= "Valores : ".$this->k03_anousu."-".$this->k03_instit;
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = pg_affected_rows($result);
                return true;
            }
        }
    }
    // funcao para exclusao
    function excluir ($k03_anousu=null,$k03_instit=null,$dbwhere=null) {

        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            if ($dbwhere==null || $dbwhere=="") {

                $resaco = $this->sql_record($this->sql_query_file($k03_anousu,$k03_instit));
            } else {
                $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
            }
            if (($resaco != false) || ($this->numrows!=0)) {

                for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

                    $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac,0,0);
                    $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
                    $resac  = db_query("insert into db_acountkey values($acount,1904,'$k03_anousu','E')");
                    $resac  = db_query("insert into db_acountkey values($acount,10716,'$k03_instit','E')");
                    $resac  = db_query("insert into db_acount values($acount,318,1904,'','".AddSlashes(pg_result($resaco,$iresaco,'k03_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    $resac  = db_query("insert into db_acount values($acount,318,10716,'','".AddSlashes(pg_result($resaco,$iresaco,'k03_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    $resac  = db_query("insert into db_acount values($acount,318,1905,'','".AddSlashes(pg_result($resaco,$iresaco,'k03_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    $resac  = db_query("insert into db_acount values($acount,318,1906,'','".AddSlashes(pg_result($resaco,$iresaco,'k03_defope'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    $resac  = db_query("insert into db_acount values($acount,318,1907,'','".AddSlashes(pg_result($resaco,$iresaco,'k03_recjur'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    $resac  = db_query("insert into db_acount values($acount,318,1908,'','".AddSlashes(pg_result($resaco,$iresaco,'k03_numsli'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    $resac  = db_query("insert into db_acount values($acount,318,1909,'','".AddSlashes(pg_result($resaco,$iresaco,'k03_impend'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    $resac  = db_query("insert into db_acount values($acount,318,1910,'','".AddSlashes(pg_result($resaco,$iresaco,'k03_unipri'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    $resac  = db_query("insert into db_acount values($acount,318,1911,'','".AddSlashes(pg_result($resaco,$iresaco,'k03_codbco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    $resac  = db_query("insert into db_acount values($acount,318,1912,'','".AddSlashes(pg_result($resaco,$iresaco,'k03_codage'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    $resac  = db_query("insert into db_acount values($acount,318,1913,'','".AddSlashes(pg_result($resaco,$iresaco,'k03_recmul'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    $resac  = db_query("insert into db_acount values($acount,318,1914,'','".AddSlashes(pg_result($resaco,$iresaco,'k03_calrec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    $resac  = db_query("insert into db_acount values($acount,318,1915,'','".AddSlashes(pg_result($resaco,$iresaco,'k03_msg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    $resac  = db_query("insert into db_acount values($acount,318,7918,'','".AddSlashes(pg_result($resaco,$iresaco,'k03_msgcarne'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    $resac  = db_query("insert into db_acount values($acount,318,7925,'','".AddSlashes(pg_result($resaco,$iresaco,'k03_msgbanco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    $resac  = db_query("insert into db_acount values($acount,318,7943,'','".AddSlashes(pg_result($resaco,$iresaco,'k03_certissvar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    $resac  = db_query("insert into db_acount values($acount,318,8737,'','".AddSlashes(pg_result($resaco,$iresaco,'k03_diasjust'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    $resac  = db_query("insert into db_acount values($acount,318,8797,'','".AddSlashes(pg_result($resaco,$iresaco,'k03_reccert'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    $resac  = db_query("insert into db_acount values($acount,318,8799,'','".AddSlashes(pg_result($resaco,$iresaco,'k03_taxagrupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    $resac  = db_query("insert into db_acount values($acount,318,9419,'','".AddSlashes(pg_result($resaco,$iresaco,'k03_tipocodcert'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    $resac  = db_query("insert into db_acount values($acount,318,11859,'','".AddSlashes(pg_result($resaco,$iresaco,'k03_reciboprot'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    $resac  = db_query("insert into db_acount values($acount,318,14400,'','".AddSlashes(pg_result($resaco,$iresaco,'k03_regracnd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    $resac  = db_query("insert into db_acount values($acount,318,14484,'','".AddSlashes(pg_result($resaco,$iresaco,'k03_reciboprotretencao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    $resac  = db_query("insert into db_acount values($acount,318,14587,'','".AddSlashes(pg_result($resaco,$iresaco,'k03_tipocertidao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    $resac  = db_query("insert into db_acount values($acount,318,15036,'','".AddSlashes(pg_result($resaco,$iresaco,'k03_separajurmulparc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    $resac  = db_query("insert into db_acount values($acount,318,17195,'','".AddSlashes(pg_result($resaco,$iresaco,'k03_respcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    $resac  = db_query("insert into db_acount values($acount,318,17196,'','".AddSlashes(pg_result($resaco,$iresaco,'k03_respcargo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    $resac  = db_query("insert into db_acount values($acount,318,17943,'','".AddSlashes(pg_result($resaco,$iresaco,'k03_msgautent'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    $resac  = db_query("insert into db_acount values($acount,318,18059,'','".AddSlashes(pg_result($resaco,$iresaco,'k03_toleranciapgtoparc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    $resac  = db_query("insert into db_acount values($acount,318,18150,'','".AddSlashes(pg_result($resaco,$iresaco,'k03_pgtoparcial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    $resac  = db_query("insert into db_acount values($acount,318,18429,'','".AddSlashes(pg_result($resaco,$iresaco,'k03_reemissaorecibo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    $resac  = db_query("insert into db_acount values($acount,318,18468,'','".AddSlashes(pg_result($resaco,$iresaco,'k03_opcaoemissparcela'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    $resac  = db_query("insert into db_acount values($acount,318,18874,'','".AddSlashes(pg_result($resaco,$iresaco,'k03_numprepgtoparcial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    $resac  = db_query("insert into db_acount values($acount,318,19223,'','".AddSlashes(pg_result($resaco,$iresaco,'k03_agrupadorarquivotxtbaixabanco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    $resac  = db_query("insert into db_acount values($acount,318,19647,'','".AddSlashes(pg_result($resaco,$iresaco,'k03_receitapadraocredito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    $resac  = db_query("insert into db_acount values($acount,318,20229,'','".AddSlashes(pg_result($resaco,$iresaco,'k03_diasvalidadecertidao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    $resac  = db_query("insert into db_acount values($acount,318,20230,'','".AddSlashes(pg_result($resaco,$iresaco,'k03_diasreemissaocertidao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    $resac  = db_query("insert into db_acount values($acount,318,20614,'','".AddSlashes(pg_result($resaco,$iresaco,'k03_toleranciacredito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                }
            }
        }
        $sql = " delete from numpref
                    where ";
        $sql2 = "";
        if($dbwhere==null || $dbwhere ==""){
            if($k03_anousu != ""){
                if($sql2!=""){
                    $sql2 .= " and ";
                }
                $sql2 .= " k03_anousu = $k03_anousu ";
            }
            if($k03_instit != ""){
                if($sql2!=""){
                    $sql2 .= " and ";
                }
                $sql2 .= " k03_instit = $k03_instit ";
            }
        }else{
            $sql2 = $dbwhere;
        }
        $result = db_query($sql.$sql2);
        if($result==false){
            $this->erro_banco = str_replace("\n","",@pg_last_error());
            $this->erro_sql   = "Numerações nao Excluído. Exclusão Abortada.\\n";
            $this->erro_sql .= "Valores : ".$k03_anousu."-".$k03_instit;
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            $this->numrows_excluir = 0;
            return false;
        }else{
            if(pg_affected_rows($result)==0){
                $this->erro_banco = "";
                $this->erro_sql = "Numerações nao Encontrado. Exclusão não Efetuada.\\n";
                $this->erro_sql .= "Valores : ".$k03_anousu."-".$k03_instit;
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "1";
                $this->numrows_excluir = 0;
                return true;
            }else{
                $this->erro_banco = "";
                $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
                $this->erro_sql .= "Valores : ".$k03_anousu."-".$k03_instit;
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "1";
                $this->numrows_excluir = pg_affected_rows($result);
                return true;
            }
        }
    }
    // funcao do recordset
    function sql_record($sql) {
        $result = db_query($sql);
        if($result==false){
            $this->numrows    = 0;
            $this->erro_banco = str_replace("\n","",@pg_last_error());
            $this->erro_sql   = "Erro ao selecionar os registros.";
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        $this->numrows = pg_numrows($result);
        if($this->numrows==0){
            $this->erro_banco = "";
            $this->erro_sql   = "Record Vazio na Tabela:numpref";
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        return $result;
    }
    // funcao do sql
    function sql_query ( $k03_anousu=null,$k03_instit=null,$campos="*",$ordem=null,$dbwhere=""){
        $sql = "select ";
        if($campos != "*" ){
            $campos_sql = split("#",$campos);
            $virgula = "";
            for($i=0;$i<sizeof($campos_sql);$i++){
                $sql .= $virgula.$campos_sql[$i];
                $virgula = ",";
            }
        }else{
            $sql .= $campos;
        }
        $sql .= " from numpref ";
        $sql .= "      left  join cgm as cgm2     on cgm2.z01_numcgm            = numpref.k03_respcgm";
        $sql .= "      inner join arretipo  on  arretipo.k00_tipo = numpref.k03_reciboprotretencao";
        $sql .= "      inner join db_config  on  db_config.codigo = numpref.k03_instit";
        $sql .= "      left  join rhfuncao  on  rhfuncao.rh37_funcao = numpref.k03_respcargo";
        $sql .= "                                and rhfuncao.rh37_instit       = numpref.k03_instit";
        $sql .= "      inner join taxagrupo  on  taxagrupo.k06_taxagrupo = numpref.k03_taxagrupo";
        $sql .= "      inner join db_config  as a on   a.codigo = arretipo.k00_instit";
        $sql .= "      inner join cadtipo  on  cadtipo.k03_tipo = arretipo.k03_tipo";
        $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
        $sql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
        $sql .= "      left  join db_config  as b on b.codigo                   = rhfuncao.rh37_instit";
        $sql .= "      left  join tabrec          on tabrec.k02_codigo          = numpref.k03_receitapadraocredito";
        $sql2 = "";
        if($dbwhere==""){
            if($k03_anousu!=null ){
                $sql2 .= " where numpref.k03_anousu = $k03_anousu ";
            }
            if($k03_instit!=null ){
                if($sql2!=""){
                    $sql2 .= " and ";
                }else{
                    $sql2 .= " where ";
                }
                $sql2 .= " numpref.k03_instit = $k03_instit ";
            }
        }else if($dbwhere != ""){
            $sql2 = " where $dbwhere";
        }
        $sql .= $sql2;
        if($ordem != null ){
            $sql .= " order by ";
            $campos_sql = split("#",$ordem);
            $virgula = "";
            for($i=0;$i<sizeof($campos_sql);$i++){
                $sql .= $virgula.$campos_sql[$i];
                $virgula = ",";
            }
        }
        return $sql;
    }
    // funcao do sql
    function sql_query_file ( $k03_anousu=null,$k03_instit=null,$campos="*",$ordem=null,$dbwhere=""){
        $sql = "select ";
        if($campos != "*" ){
            $campos_sql = split("#",$campos);
            $virgula = "";
            for($i=0;$i<sizeof($campos_sql);$i++){
                $sql .= $virgula.$campos_sql[$i];
                $virgula = ",";
            }
        }else{
            $sql .= $campos;
        }
        $sql .= " from numpref ";
        $sql2 = "";
        if($dbwhere==""){
            if($k03_anousu!=null ){
                $sql2 .= " where numpref.k03_anousu = $k03_anousu ";
            }
            if($k03_instit!=null ){
                if($sql2!=""){
                    $sql2 .= " and ";
                }else{
                    $sql2 .= " where ";
                }
                $sql2 .= " numpref.k03_instit = $k03_instit ";
            }
        }else if($dbwhere != ""){
            $sql2 = " where $dbwhere";
        }
        $sql .= $sql2;
        if($ordem != null ){
            $sql .= " order by ";
            $campos_sql = split("#",$ordem);
            $virgula = "";
            for($i=0;$i<sizeof($campos_sql);$i++){
                $sql .= $virgula.$campos_sql[$i];
                $virgula = ",";
            }
        }
        return $sql;
    }

    /**
     * @return integer
     */
    function sql_numpre(){
        $result = @db_query("select nextval('numpref_k03_numpre_seq')");
        return pg_result($result,0,0);
    }

    /**
     * @return int
     */
    public static function getNumpre() {

        $dao = new cl_numpref();
        return $dao->sql_numpre();
    }
}

