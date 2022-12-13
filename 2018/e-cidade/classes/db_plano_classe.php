<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

//MODULO: contabil
//CLASSE DA ENTIDADE plano
class cl_plano { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $c01_anousu = 0; 
   var $c01_estrut = null; 
   var $c01_reduz = 0; 
   var $c01_descr = null; 
   var $c01_dbabre = 0; 
   var $c01_crabre = 0; 
   var $c01_db01 = 0; 
   var $c01_cr01 = 0; 
   var $c01_db02 = 0; 
   var $c01_cr02 = 0; 
   var $c01_db03 = 0; 
   var $c01_cr03 = 0; 
   var $c01_db04 = 0; 
   var $c01_cr04 = 0; 
   var $c01_db05 = 0; 
   var $c01_cr05 = 0; 
   var $c01_db06 = 0; 
   var $c01_cr06 = 0; 
   var $c01_db07 = 0; 
   var $c01_cr07 = 0; 
   var $c01_db08 = 0; 
   var $c01_cr08 = 0; 
   var $c01_db09 = 0; 
   var $c01_cr09 = 0; 
   var $c01_db10 = 0; 
   var $c01_cr10 = 0; 
   var $c01_db11 = 0; 
   var $c01_cr11 = 0; 
   var $c01_db12 = 0; 
   var $c01_cr12 = 0; 
   var $c01_codtce = null; 
   var $c01_recurs = null; 
   var $c01_codbco = null; 
   var $c01_codage = null; 
   var $c01_codcta = null; 
   var $c01_tpcont = null; 
   var $c01_clarec = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 c01_anousu = int4 = Exercício 
                 c01_estrut = varchar(13) = Código Estrutural 
                 c01_reduz = int4 = Código Reduzido 
                 c01_descr = varchar(40) = Descrição da Conta 
                 c01_dbabre = float8 = Valor do Débito de  Abertura 
                 c01_crabre = float8 = Valor do Credito de Abertura 
                 c01_db01 = float8 = Valor do Débito Mes de Janeiro 
                 c01_cr01 = float8 = Valor do Crédito do mes de Janeiro 
                 c01_db02 = float8 = Valor do Débito Mes de Fevereiro 
                 c01_cr02 = float8 = Valor do Crédito do Mes de Fevereiro 
                 c01_db03 = float8 = Valor do Débito Mes de Março 
                 c01_cr03 = float8 = Valor do Crédito do Mes de  Março 
                 c01_db04 = float8 = Valor do Débito do Mes de Abril 
                 c01_cr04 = float8 = Valor do Credito mes de Abril 
                 c01_db05 = float8 = Valor do Débito Mes de Maio 
                 c01_cr05 = float8 = Valor do Credito Mes de Maio 
                 c01_db06 = float8 = Valor do Débito do Mes de Junho 
                 c01_cr06 = float8 = Valor do Crédito do Mes de Junho 
                 c01_db07 = float8 = Valor do Debito do Mes de Julho 
                 c01_cr07 = float8 = Valor do Credito do Mes de Julho 
                 c01_db08 = float8 = Valor do Debito do Mes de Agosto 
                 c01_cr08 = float8 = Valor  do Crédito do Mes de Agosto 
                 c01_db09 = float8 = Valor do Débito do Mes de Setembro 
                 c01_cr09 = float8 = Valor do Crédito do Mes de Setembro 
                 c01_db10 = float8 = Valor do Débito do Mes de Outubro 
                 c01_cr10 = float8 = Valor do Crédito do Mes de Outubro 
                 c01_db11 = float8 = Valor do Débito do Mes de Novembro 
                 c01_cr11 = float8 = Valor do Crédito do mes de Novembro 
                 c01_db12 = float8 = Valor do Crédito do Mes de Dezembro 
                 c01_cr12 = float8 = valor do Crédito do Mes de Dezembro 
                 c01_codtce = char(13) = Código do TCE 
                 c01_recurs = char(4) = Código do Recurso 
                 c01_codbco = char(5) = Código do Banco 
                 c01_codage = char(5) = Código da Agência 
                 c01_codcta = char(20) = Código da Conta Bancaria 
                 c01_tpcont = char(1) = Tipo de Conta 
                 c01_clarec = int4 = Classificação 
                 ";
   //funcao construtor da classe 
   function cl_plano() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("plano"); 
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
       $this->c01_anousu = ($this->c01_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["c01_anousu"]:$this->c01_anousu);
       $this->c01_estrut = ($this->c01_estrut == ""?@$GLOBALS["HTTP_POST_VARS"]["c01_estrut"]:$this->c01_estrut);
       $this->c01_reduz = ($this->c01_reduz == ""?@$GLOBALS["HTTP_POST_VARS"]["c01_reduz"]:$this->c01_reduz);
       $this->c01_descr = ($this->c01_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["c01_descr"]:$this->c01_descr);
       $this->c01_dbabre = ($this->c01_dbabre == ""?@$GLOBALS["HTTP_POST_VARS"]["c01_dbabre"]:$this->c01_dbabre);
       $this->c01_crabre = ($this->c01_crabre == ""?@$GLOBALS["HTTP_POST_VARS"]["c01_crabre"]:$this->c01_crabre);
       $this->c01_db01 = ($this->c01_db01 == ""?@$GLOBALS["HTTP_POST_VARS"]["c01_db01"]:$this->c01_db01);
       $this->c01_cr01 = ($this->c01_cr01 == ""?@$GLOBALS["HTTP_POST_VARS"]["c01_cr01"]:$this->c01_cr01);
       $this->c01_db02 = ($this->c01_db02 == ""?@$GLOBALS["HTTP_POST_VARS"]["c01_db02"]:$this->c01_db02);
       $this->c01_cr02 = ($this->c01_cr02 == ""?@$GLOBALS["HTTP_POST_VARS"]["c01_cr02"]:$this->c01_cr02);
       $this->c01_db03 = ($this->c01_db03 == ""?@$GLOBALS["HTTP_POST_VARS"]["c01_db03"]:$this->c01_db03);
       $this->c01_cr03 = ($this->c01_cr03 == ""?@$GLOBALS["HTTP_POST_VARS"]["c01_cr03"]:$this->c01_cr03);
       $this->c01_db04 = ($this->c01_db04 == ""?@$GLOBALS["HTTP_POST_VARS"]["c01_db04"]:$this->c01_db04);
       $this->c01_cr04 = ($this->c01_cr04 == ""?@$GLOBALS["HTTP_POST_VARS"]["c01_cr04"]:$this->c01_cr04);
       $this->c01_db05 = ($this->c01_db05 == ""?@$GLOBALS["HTTP_POST_VARS"]["c01_db05"]:$this->c01_db05);
       $this->c01_cr05 = ($this->c01_cr05 == ""?@$GLOBALS["HTTP_POST_VARS"]["c01_cr05"]:$this->c01_cr05);
       $this->c01_db06 = ($this->c01_db06 == ""?@$GLOBALS["HTTP_POST_VARS"]["c01_db06"]:$this->c01_db06);
       $this->c01_cr06 = ($this->c01_cr06 == ""?@$GLOBALS["HTTP_POST_VARS"]["c01_cr06"]:$this->c01_cr06);
       $this->c01_db07 = ($this->c01_db07 == ""?@$GLOBALS["HTTP_POST_VARS"]["c01_db07"]:$this->c01_db07);
       $this->c01_cr07 = ($this->c01_cr07 == ""?@$GLOBALS["HTTP_POST_VARS"]["c01_cr07"]:$this->c01_cr07);
       $this->c01_db08 = ($this->c01_db08 == ""?@$GLOBALS["HTTP_POST_VARS"]["c01_db08"]:$this->c01_db08);
       $this->c01_cr08 = ($this->c01_cr08 == ""?@$GLOBALS["HTTP_POST_VARS"]["c01_cr08"]:$this->c01_cr08);
       $this->c01_db09 = ($this->c01_db09 == ""?@$GLOBALS["HTTP_POST_VARS"]["c01_db09"]:$this->c01_db09);
       $this->c01_cr09 = ($this->c01_cr09 == ""?@$GLOBALS["HTTP_POST_VARS"]["c01_cr09"]:$this->c01_cr09);
       $this->c01_db10 = ($this->c01_db10 == ""?@$GLOBALS["HTTP_POST_VARS"]["c01_db10"]:$this->c01_db10);
       $this->c01_cr10 = ($this->c01_cr10 == ""?@$GLOBALS["HTTP_POST_VARS"]["c01_cr10"]:$this->c01_cr10);
       $this->c01_db11 = ($this->c01_db11 == ""?@$GLOBALS["HTTP_POST_VARS"]["c01_db11"]:$this->c01_db11);
       $this->c01_cr11 = ($this->c01_cr11 == ""?@$GLOBALS["HTTP_POST_VARS"]["c01_cr11"]:$this->c01_cr11);
       $this->c01_db12 = ($this->c01_db12 == ""?@$GLOBALS["HTTP_POST_VARS"]["c01_db12"]:$this->c01_db12);
       $this->c01_cr12 = ($this->c01_cr12 == ""?@$GLOBALS["HTTP_POST_VARS"]["c01_cr12"]:$this->c01_cr12);
       $this->c01_codtce = ($this->c01_codtce == ""?@$GLOBALS["HTTP_POST_VARS"]["c01_codtce"]:$this->c01_codtce);
       $this->c01_recurs = ($this->c01_recurs == ""?@$GLOBALS["HTTP_POST_VARS"]["c01_recurs"]:$this->c01_recurs);
       $this->c01_codbco = ($this->c01_codbco == ""?@$GLOBALS["HTTP_POST_VARS"]["c01_codbco"]:$this->c01_codbco);
       $this->c01_codage = ($this->c01_codage == ""?@$GLOBALS["HTTP_POST_VARS"]["c01_codage"]:$this->c01_codage);
       $this->c01_codcta = ($this->c01_codcta == ""?@$GLOBALS["HTTP_POST_VARS"]["c01_codcta"]:$this->c01_codcta);
       $this->c01_tpcont = ($this->c01_tpcont == ""?@$GLOBALS["HTTP_POST_VARS"]["c01_tpcont"]:$this->c01_tpcont);
       $this->c01_clarec = ($this->c01_clarec == ""?@$GLOBALS["HTTP_POST_VARS"]["c01_clarec"]:$this->c01_clarec);
     }else{
       $this->c01_anousu = ($this->c01_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["c01_anousu"]:$this->c01_anousu);
       $this->c01_estrut = ($this->c01_estrut == ""?@$GLOBALS["HTTP_POST_VARS"]["c01_estrut"]:$this->c01_estrut);
     }
   }
   // funcao para inclusao
   function incluir ($c01_anousu,$c01_estrut){ 
      $this->atualizacampos();
     if($this->c01_reduz == null ){ 
       $this->erro_sql = " Campo Código Reduzido nao Informado.";
       $this->erro_campo = "c01_reduz";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c01_descr == null ){ 
       $this->erro_sql = " Campo Descrição da Conta nao Informado.";
       $this->erro_campo = "c01_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c01_dbabre == null ){ 
       $this->erro_sql = " Campo Valor do Débito de  Abertura nao Informado.";
       $this->erro_campo = "c01_dbabre";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c01_crabre == null ){ 
       $this->erro_sql = " Campo Valor do Credito de Abertura nao Informado.";
       $this->erro_campo = "c01_crabre";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c01_db01 == null ){ 
       $this->erro_sql = " Campo Valor do Débito Mes de Janeiro nao Informado.";
       $this->erro_campo = "c01_db01";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c01_cr01 == null ){ 
       $this->erro_sql = " Campo Valor do Crédito do mes de Janeiro nao Informado.";
       $this->erro_campo = "c01_cr01";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c01_db02 == null ){ 
       $this->erro_sql = " Campo Valor do Débito Mes de Fevereiro nao Informado.";
       $this->erro_campo = "c01_db02";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c01_cr02 == null ){ 
       $this->erro_sql = " Campo Valor do Crédito do Mes de Fevereiro nao Informado.";
       $this->erro_campo = "c01_cr02";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c01_db03 == null ){ 
       $this->erro_sql = " Campo Valor do Débito Mes de Março nao Informado.";
       $this->erro_campo = "c01_db03";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c01_cr03 == null ){ 
       $this->erro_sql = " Campo Valor do Crédito do Mes de  Março nao Informado.";
       $this->erro_campo = "c01_cr03";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c01_db04 == null ){ 
       $this->erro_sql = " Campo Valor do Débito do Mes de Abril nao Informado.";
       $this->erro_campo = "c01_db04";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c01_cr04 == null ){ 
       $this->erro_sql = " Campo Valor do Credito mes de Abril nao Informado.";
       $this->erro_campo = "c01_cr04";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c01_db05 == null ){ 
       $this->erro_sql = " Campo Valor do Débito Mes de Maio nao Informado.";
       $this->erro_campo = "c01_db05";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c01_cr05 == null ){ 
       $this->erro_sql = " Campo Valor do Credito Mes de Maio nao Informado.";
       $this->erro_campo = "c01_cr05";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c01_db06 == null ){ 
       $this->erro_sql = " Campo Valor do Débito do Mes de Junho nao Informado.";
       $this->erro_campo = "c01_db06";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c01_cr06 == null ){ 
       $this->erro_sql = " Campo Valor do Crédito do Mes de Junho nao Informado.";
       $this->erro_campo = "c01_cr06";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c01_db07 == null ){ 
       $this->erro_sql = " Campo Valor do Debito do Mes de Julho nao Informado.";
       $this->erro_campo = "c01_db07";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c01_cr07 == null ){ 
       $this->erro_sql = " Campo Valor do Credito do Mes de Julho nao Informado.";
       $this->erro_campo = "c01_cr07";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c01_db08 == null ){ 
       $this->erro_sql = " Campo Valor do Debito do Mes de Agosto nao Informado.";
       $this->erro_campo = "c01_db08";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c01_cr08 == null ){ 
       $this->erro_sql = " Campo Valor  do Crédito do Mes de Agosto nao Informado.";
       $this->erro_campo = "c01_cr08";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c01_db09 == null ){ 
       $this->erro_sql = " Campo Valor do Débito do Mes de Setembro nao Informado.";
       $this->erro_campo = "c01_db09";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c01_cr09 == null ){ 
       $this->erro_sql = " Campo Valor do Crédito do Mes de Setembro nao Informado.";
       $this->erro_campo = "c01_cr09";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c01_db10 == null ){ 
       $this->erro_sql = " Campo Valor do Débito do Mes de Outubro nao Informado.";
       $this->erro_campo = "c01_db10";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c01_cr10 == null ){ 
       $this->erro_sql = " Campo Valor do Crédito do Mes de Outubro nao Informado.";
       $this->erro_campo = "c01_cr10";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c01_db11 == null ){ 
       $this->erro_sql = " Campo Valor do Débito do Mes de Novembro nao Informado.";
       $this->erro_campo = "c01_db11";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c01_cr11 == null ){ 
       $this->erro_sql = " Campo Valor do Crédito do mes de Novembro nao Informado.";
       $this->erro_campo = "c01_cr11";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c01_db12 == null ){ 
       $this->erro_sql = " Campo Valor do Crédito do Mes de Dezembro nao Informado.";
       $this->erro_campo = "c01_db12";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c01_cr12 == null ){ 
       $this->erro_sql = " Campo valor do Crédito do Mes de Dezembro nao Informado.";
       $this->erro_campo = "c01_cr12";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c01_codtce == null ){ 
       $this->erro_sql = " Campo Código do TCE nao Informado.";
       $this->erro_campo = "c01_codtce";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c01_recurs == null ){ 
       $this->erro_sql = " Campo Código do Recurso nao Informado.";
       $this->erro_campo = "c01_recurs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c01_codbco == null ){ 
       $this->erro_sql = " Campo Código do Banco nao Informado.";
       $this->erro_campo = "c01_codbco";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c01_codage == null ){ 
       $this->erro_sql = " Campo Código da Agência nao Informado.";
       $this->erro_campo = "c01_codage";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c01_codcta == null ){ 
       $this->erro_sql = " Campo Código da Conta Bancaria nao Informado.";
       $this->erro_campo = "c01_codcta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c01_tpcont == null ){ 
       $this->erro_sql = " Campo Tipo de Conta nao Informado.";
       $this->erro_campo = "c01_tpcont";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c01_clarec == null ){ 
       $this->erro_sql = " Campo Classificação nao Informado.";
       $this->erro_campo = "c01_clarec";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->c01_anousu = $c01_anousu; 
       $this->c01_estrut = $c01_estrut; 
     if(($this->c01_anousu == null) || ($this->c01_anousu == "") ){ 
       $this->erro_sql = " Campo c01_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->c01_estrut == null) || ($this->c01_estrut == "") ){ 
       $this->erro_sql = " Campo c01_estrut nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $result = @pg_query("insert into plano(
                                       c01_anousu 
                                      ,c01_estrut 
                                      ,c01_reduz 
                                      ,c01_descr 
                                      ,c01_dbabre 
                                      ,c01_crabre 
                                      ,c01_db01 
                                      ,c01_cr01 
                                      ,c01_db02 
                                      ,c01_cr02 
                                      ,c01_db03 
                                      ,c01_cr03 
                                      ,c01_db04 
                                      ,c01_cr04 
                                      ,c01_db05 
                                      ,c01_cr05 
                                      ,c01_db06 
                                      ,c01_cr06 
                                      ,c01_db07 
                                      ,c01_cr07 
                                      ,c01_db08 
                                      ,c01_cr08 
                                      ,c01_db09 
                                      ,c01_cr09 
                                      ,c01_db10 
                                      ,c01_cr10 
                                      ,c01_db11 
                                      ,c01_cr11 
                                      ,c01_db12 
                                      ,c01_cr12 
                                      ,c01_codtce 
                                      ,c01_recurs 
                                      ,c01_codbco 
                                      ,c01_codage 
                                      ,c01_codcta 
                                      ,c01_tpcont 
                                      ,c01_clarec 
                       )
                values (
                                $this->c01_anousu 
                               ,'$this->c01_estrut' 
                               ,$this->c01_reduz 
                               ,'$this->c01_descr' 
                               ,$this->c01_dbabre 
                               ,$this->c01_crabre 
                               ,$this->c01_db01 
                               ,$this->c01_cr01 
                               ,$this->c01_db02 
                               ,$this->c01_cr02 
                               ,$this->c01_db03 
                               ,$this->c01_cr03 
                               ,$this->c01_db04 
                               ,$this->c01_cr04 
                               ,$this->c01_db05 
                               ,$this->c01_cr05 
                               ,$this->c01_db06 
                               ,$this->c01_cr06 
                               ,$this->c01_db07 
                               ,$this->c01_cr07 
                               ,$this->c01_db08 
                               ,$this->c01_cr08 
                               ,$this->c01_db09 
                               ,$this->c01_cr09 
                               ,$this->c01_db10 
                               ,$this->c01_cr10 
                               ,$this->c01_db11 
                               ,$this->c01_cr11 
                               ,$this->c01_db12 
                               ,$this->c01_cr12 
                               ,'$this->c01_codtce' 
                               ,'$this->c01_recurs' 
                               ,'$this->c01_codbco' 
                               ,'$this->c01_codage' 
                               ,'$this->c01_codcta' 
                               ,'$this->c01_tpcont' 
                               ,$this->c01_clarec 
                      )");
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Plano de Contas ($this->c01_anousu."-".$this->c01_estrut) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Plano de Contas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Plano de Contas ($this->c01_anousu."-".$this->c01_estrut) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao Efetivada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c01_anousu."-".$this->c01_estrut;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $resaco = $this->sql_record($this->sql_query_file($this->c01_anousu,$this->c01_estrut));
     $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
     $acount = pg_result($resac,0,0);
     $resac = pg_query("insert into db_acountkey values($acount,1274,'$this->c01_anousu','I')");
     $resac = pg_query("insert into db_acountkey values($acount,1275,'$this->c01_estrut','I')");
     $resac = pg_query("insert into db_acount values($acount,227,1274,'','".pg_result($resaco,0,'c01_anousu')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1275,'','".pg_result($resaco,0,'c01_estrut')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1276,'','".pg_result($resaco,0,'c01_reduz')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1277,'','".pg_result($resaco,0,'c01_descr')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1278,'','".pg_result($resaco,0,'c01_dbabre')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1279,'','".pg_result($resaco,0,'c01_crabre')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1280,'','".pg_result($resaco,0,'c01_db01')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1281,'','".pg_result($resaco,0,'c01_cr01')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1282,'','".pg_result($resaco,0,'c01_db02')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1283,'','".pg_result($resaco,0,'c01_cr02')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1284,'','".pg_result($resaco,0,'c01_db03')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1285,'','".pg_result($resaco,0,'c01_cr03')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1286,'','".pg_result($resaco,0,'c01_db04')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1287,'','".pg_result($resaco,0,'c01_cr04')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1288,'','".pg_result($resaco,0,'c01_db05')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1289,'','".pg_result($resaco,0,'c01_cr05')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1290,'','".pg_result($resaco,0,'c01_db06')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1291,'','".pg_result($resaco,0,'c01_cr06')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1292,'','".pg_result($resaco,0,'c01_db07')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1293,'','".pg_result($resaco,0,'c01_cr07')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1294,'','".pg_result($resaco,0,'c01_db08')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1295,'','".pg_result($resaco,0,'c01_cr08')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1296,'','".pg_result($resaco,0,'c01_db09')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1297,'','".pg_result($resaco,0,'c01_cr09')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1298,'','".pg_result($resaco,0,'c01_db10')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1299,'','".pg_result($resaco,0,'c01_cr10')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1300,'','".pg_result($resaco,0,'c01_db11')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1301,'','".pg_result($resaco,0,'c01_cr11')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1302,'','".pg_result($resaco,0,'c01_db12')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1303,'','".pg_result($resaco,0,'c01_cr12')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1304,'','".pg_result($resaco,0,'c01_codtce')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1305,'','".pg_result($resaco,0,'c01_recurs')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1306,'','".pg_result($resaco,0,'c01_codbco')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1307,'','".pg_result($resaco,0,'c01_codage')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1308,'','".pg_result($resaco,0,'c01_codcta')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1309,'','".pg_result($resaco,0,'c01_tpcont')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1310,'','".pg_result($resaco,0,'c01_clarec')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     pg_free_result($resaco);
     return true;
   } 
   // funcao para alteracao
   function alterar ($c01_anousu=null,$c01_estrut=null) { 
      $this->atualizacampos();
     $sql = " update plano set ";
     $virgula = "";
     if(isset($GLOBALS["HTTP_POST_VARS"]["c01_anousu"])){ 
       $sql  .= $virgula." c01_anousu = $this->c01_anousu ";
       $virgula = ",";
       if($this->c01_anousu == null ){ 
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "c01_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(isset($GLOBALS["HTTP_POST_VARS"]["c01_estrut"])){ 
       $sql  .= $virgula." c01_estrut = '$this->c01_estrut' ";
       $virgula = ",";
       if($this->c01_estrut == null ){ 
         $this->erro_sql = " Campo Código Estrutural nao Informado.";
         $this->erro_campo = "c01_estrut";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(isset($GLOBALS["HTTP_POST_VARS"]["c01_reduz"])){ 
       $sql  .= $virgula." c01_reduz = $this->c01_reduz ";
       $virgula = ",";
       if($this->c01_reduz == null ){ 
         $this->erro_sql = " Campo Código Reduzido nao Informado.";
         $this->erro_campo = "c01_reduz";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(isset($GLOBALS["HTTP_POST_VARS"]["c01_descr"])){ 
       $sql  .= $virgula." c01_descr = '$this->c01_descr' ";
       $virgula = ",";
       if($this->c01_descr == null ){ 
         $this->erro_sql = " Campo Descrição da Conta nao Informado.";
         $this->erro_campo = "c01_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(isset($GLOBALS["HTTP_POST_VARS"]["c01_dbabre"])){ 
       $sql  .= $virgula." c01_dbabre = $this->c01_dbabre ";
       $virgula = ",";
       if($this->c01_dbabre == null ){ 
         $this->erro_sql = " Campo Valor do Débito de  Abertura nao Informado.";
         $this->erro_campo = "c01_dbabre";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(isset($GLOBALS["HTTP_POST_VARS"]["c01_crabre"])){ 
       $sql  .= $virgula." c01_crabre = $this->c01_crabre ";
       $virgula = ",";
       if($this->c01_crabre == null ){ 
         $this->erro_sql = " Campo Valor do Credito de Abertura nao Informado.";
         $this->erro_campo = "c01_crabre";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(isset($GLOBALS["HTTP_POST_VARS"]["c01_db01"])){ 
       $sql  .= $virgula." c01_db01 = $this->c01_db01 ";
       $virgula = ",";
       if($this->c01_db01 == null ){ 
         $this->erro_sql = " Campo Valor do Débito Mes de Janeiro nao Informado.";
         $this->erro_campo = "c01_db01";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(isset($GLOBALS["HTTP_POST_VARS"]["c01_cr01"])){ 
       $sql  .= $virgula." c01_cr01 = $this->c01_cr01 ";
       $virgula = ",";
       if($this->c01_cr01 == null ){ 
         $this->erro_sql = " Campo Valor do Crédito do mes de Janeiro nao Informado.";
         $this->erro_campo = "c01_cr01";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(isset($GLOBALS["HTTP_POST_VARS"]["c01_db02"])){ 
       $sql  .= $virgula." c01_db02 = $this->c01_db02 ";
       $virgula = ",";
       if($this->c01_db02 == null ){ 
         $this->erro_sql = " Campo Valor do Débito Mes de Fevereiro nao Informado.";
         $this->erro_campo = "c01_db02";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(isset($GLOBALS["HTTP_POST_VARS"]["c01_cr02"])){ 
       $sql  .= $virgula." c01_cr02 = $this->c01_cr02 ";
       $virgula = ",";
       if($this->c01_cr02 == null ){ 
         $this->erro_sql = " Campo Valor do Crédito do Mes de Fevereiro nao Informado.";
         $this->erro_campo = "c01_cr02";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(isset($GLOBALS["HTTP_POST_VARS"]["c01_db03"])){ 
       $sql  .= $virgula." c01_db03 = $this->c01_db03 ";
       $virgula = ",";
       if($this->c01_db03 == null ){ 
         $this->erro_sql = " Campo Valor do Débito Mes de Março nao Informado.";
         $this->erro_campo = "c01_db03";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(isset($GLOBALS["HTTP_POST_VARS"]["c01_cr03"])){ 
       $sql  .= $virgula." c01_cr03 = $this->c01_cr03 ";
       $virgula = ",";
       if($this->c01_cr03 == null ){ 
         $this->erro_sql = " Campo Valor do Crédito do Mes de  Março nao Informado.";
         $this->erro_campo = "c01_cr03";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(isset($GLOBALS["HTTP_POST_VARS"]["c01_db04"])){ 
       $sql  .= $virgula." c01_db04 = $this->c01_db04 ";
       $virgula = ",";
       if($this->c01_db04 == null ){ 
         $this->erro_sql = " Campo Valor do Débito do Mes de Abril nao Informado.";
         $this->erro_campo = "c01_db04";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(isset($GLOBALS["HTTP_POST_VARS"]["c01_cr04"])){ 
       $sql  .= $virgula." c01_cr04 = $this->c01_cr04 ";
       $virgula = ",";
       if($this->c01_cr04 == null ){ 
         $this->erro_sql = " Campo Valor do Credito mes de Abril nao Informado.";
         $this->erro_campo = "c01_cr04";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(isset($GLOBALS["HTTP_POST_VARS"]["c01_db05"])){ 
       $sql  .= $virgula." c01_db05 = $this->c01_db05 ";
       $virgula = ",";
       if($this->c01_db05 == null ){ 
         $this->erro_sql = " Campo Valor do Débito Mes de Maio nao Informado.";
         $this->erro_campo = "c01_db05";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(isset($GLOBALS["HTTP_POST_VARS"]["c01_cr05"])){ 
       $sql  .= $virgula." c01_cr05 = $this->c01_cr05 ";
       $virgula = ",";
       if($this->c01_cr05 == null ){ 
         $this->erro_sql = " Campo Valor do Credito Mes de Maio nao Informado.";
         $this->erro_campo = "c01_cr05";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(isset($GLOBALS["HTTP_POST_VARS"]["c01_db06"])){ 
       $sql  .= $virgula." c01_db06 = $this->c01_db06 ";
       $virgula = ",";
       if($this->c01_db06 == null ){ 
         $this->erro_sql = " Campo Valor do Débito do Mes de Junho nao Informado.";
         $this->erro_campo = "c01_db06";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(isset($GLOBALS["HTTP_POST_VARS"]["c01_cr06"])){ 
       $sql  .= $virgula." c01_cr06 = $this->c01_cr06 ";
       $virgula = ",";
       if($this->c01_cr06 == null ){ 
         $this->erro_sql = " Campo Valor do Crédito do Mes de Junho nao Informado.";
         $this->erro_campo = "c01_cr06";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(isset($GLOBALS["HTTP_POST_VARS"]["c01_db07"])){ 
       $sql  .= $virgula." c01_db07 = $this->c01_db07 ";
       $virgula = ",";
       if($this->c01_db07 == null ){ 
         $this->erro_sql = " Campo Valor do Debito do Mes de Julho nao Informado.";
         $this->erro_campo = "c01_db07";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(isset($GLOBALS["HTTP_POST_VARS"]["c01_cr07"])){ 
       $sql  .= $virgula." c01_cr07 = $this->c01_cr07 ";
       $virgula = ",";
       if($this->c01_cr07 == null ){ 
         $this->erro_sql = " Campo Valor do Credito do Mes de Julho nao Informado.";
         $this->erro_campo = "c01_cr07";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(isset($GLOBALS["HTTP_POST_VARS"]["c01_db08"])){ 
       $sql  .= $virgula." c01_db08 = $this->c01_db08 ";
       $virgula = ",";
       if($this->c01_db08 == null ){ 
         $this->erro_sql = " Campo Valor do Debito do Mes de Agosto nao Informado.";
         $this->erro_campo = "c01_db08";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(isset($GLOBALS["HTTP_POST_VARS"]["c01_cr08"])){ 
       $sql  .= $virgula." c01_cr08 = $this->c01_cr08 ";
       $virgula = ",";
       if($this->c01_cr08 == null ){ 
         $this->erro_sql = " Campo Valor  do Crédito do Mes de Agosto nao Informado.";
         $this->erro_campo = "c01_cr08";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(isset($GLOBALS["HTTP_POST_VARS"]["c01_db09"])){ 
       $sql  .= $virgula." c01_db09 = $this->c01_db09 ";
       $virgula = ",";
       if($this->c01_db09 == null ){ 
         $this->erro_sql = " Campo Valor do Débito do Mes de Setembro nao Informado.";
         $this->erro_campo = "c01_db09";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(isset($GLOBALS["HTTP_POST_VARS"]["c01_cr09"])){ 
       $sql  .= $virgula." c01_cr09 = $this->c01_cr09 ";
       $virgula = ",";
       if($this->c01_cr09 == null ){ 
         $this->erro_sql = " Campo Valor do Crédito do Mes de Setembro nao Informado.";
         $this->erro_campo = "c01_cr09";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(isset($GLOBALS["HTTP_POST_VARS"]["c01_db10"])){ 
       $sql  .= $virgula." c01_db10 = $this->c01_db10 ";
       $virgula = ",";
       if($this->c01_db10 == null ){ 
         $this->erro_sql = " Campo Valor do Débito do Mes de Outubro nao Informado.";
         $this->erro_campo = "c01_db10";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(isset($GLOBALS["HTTP_POST_VARS"]["c01_cr10"])){ 
       $sql  .= $virgula." c01_cr10 = $this->c01_cr10 ";
       $virgula = ",";
       if($this->c01_cr10 == null ){ 
         $this->erro_sql = " Campo Valor do Crédito do Mes de Outubro nao Informado.";
         $this->erro_campo = "c01_cr10";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(isset($GLOBALS["HTTP_POST_VARS"]["c01_db11"])){ 
       $sql  .= $virgula." c01_db11 = $this->c01_db11 ";
       $virgula = ",";
       if($this->c01_db11 == null ){ 
         $this->erro_sql = " Campo Valor do Débito do Mes de Novembro nao Informado.";
         $this->erro_campo = "c01_db11";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(isset($GLOBALS["HTTP_POST_VARS"]["c01_cr11"])){ 
       $sql  .= $virgula." c01_cr11 = $this->c01_cr11 ";
       $virgula = ",";
       if($this->c01_cr11 == null ){ 
         $this->erro_sql = " Campo Valor do Crédito do mes de Novembro nao Informado.";
         $this->erro_campo = "c01_cr11";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(isset($GLOBALS["HTTP_POST_VARS"]["c01_db12"])){ 
       $sql  .= $virgula." c01_db12 = $this->c01_db12 ";
       $virgula = ",";
       if($this->c01_db12 == null ){ 
         $this->erro_sql = " Campo Valor do Crédito do Mes de Dezembro nao Informado.";
         $this->erro_campo = "c01_db12";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(isset($GLOBALS["HTTP_POST_VARS"]["c01_cr12"])){ 
       $sql  .= $virgula." c01_cr12 = $this->c01_cr12 ";
       $virgula = ",";
       if($this->c01_cr12 == null ){ 
         $this->erro_sql = " Campo valor do Crédito do Mes de Dezembro nao Informado.";
         $this->erro_campo = "c01_cr12";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(isset($GLOBALS["HTTP_POST_VARS"]["c01_codtce"])){ 
       $sql  .= $virgula." c01_codtce = '$this->c01_codtce' ";
       $virgula = ",";
       if($this->c01_codtce == null ){ 
         $this->erro_sql = " Campo Código do TCE nao Informado.";
         $this->erro_campo = "c01_codtce";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(isset($GLOBALS["HTTP_POST_VARS"]["c01_recurs"])){ 
       $sql  .= $virgula." c01_recurs = '$this->c01_recurs' ";
       $virgula = ",";
       if($this->c01_recurs == null ){ 
         $this->erro_sql = " Campo Código do Recurso nao Informado.";
         $this->erro_campo = "c01_recurs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(isset($GLOBALS["HTTP_POST_VARS"]["c01_codbco"])){ 
       $sql  .= $virgula." c01_codbco = '$this->c01_codbco' ";
       $virgula = ",";
       if($this->c01_codbco == null ){ 
         $this->erro_sql = " Campo Código do Banco nao Informado.";
         $this->erro_campo = "c01_codbco";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(isset($GLOBALS["HTTP_POST_VARS"]["c01_codage"])){ 
       $sql  .= $virgula." c01_codage = '$this->c01_codage' ";
       $virgula = ",";
       if($this->c01_codage == null ){ 
         $this->erro_sql = " Campo Código da Agência nao Informado.";
         $this->erro_campo = "c01_codage";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(isset($GLOBALS["HTTP_POST_VARS"]["c01_codcta"])){ 
       $sql  .= $virgula." c01_codcta = '$this->c01_codcta' ";
       $virgula = ",";
       if($this->c01_codcta == null ){ 
         $this->erro_sql = " Campo Código da Conta Bancaria nao Informado.";
         $this->erro_campo = "c01_codcta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(isset($GLOBALS["HTTP_POST_VARS"]["c01_tpcont"])){ 
       $sql  .= $virgula." c01_tpcont = '$this->c01_tpcont' ";
       $virgula = ",";
       if($this->c01_tpcont == null ){ 
         $this->erro_sql = " Campo Tipo de Conta nao Informado.";
         $this->erro_campo = "c01_tpcont";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(isset($GLOBALS["HTTP_POST_VARS"]["c01_clarec"])){ 
       $sql  .= $virgula." c01_clarec = $this->c01_clarec ";
       $virgula = ",";
       if($this->c01_clarec == null ){ 
         $this->erro_sql = " Campo Classificação nao Informado.";
         $this->erro_campo = "c01_clarec";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where  c01_anousu = $this->c01_anousu
 and  c01_estrut = '$this->c01_estrut'
";
     $resaco = $this->sql_record($this->sql_query_file($this->c01_anousu,$this->c01_estrut));
     if($this->numrows>0){       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,1274,'$this->c01_anousu','A')");
       $resac = pg_query("insert into db_acountkey values($acount,1275,'$this->c01_estrut','A')");
       if(isset($GLOBALS["HTTP_POST_VARS"]["c01_anousu"]))
         $resac = pg_query("insert into db_acount values($acount,227,1274,'$this->c01_anousu','".pg_result($resaco,0,'c01_anousu')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["c01_estrut"]))
         $resac = pg_query("insert into db_acount values($acount,227,1275,'$this->c01_estrut','".pg_result($resaco,0,'c01_estrut')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["c01_reduz"]))
         $resac = pg_query("insert into db_acount values($acount,227,1276,'$this->c01_reduz','".pg_result($resaco,0,'c01_reduz')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["c01_descr"]))
         $resac = pg_query("insert into db_acount values($acount,227,1277,'$this->c01_descr','".pg_result($resaco,0,'c01_descr')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["c01_dbabre"]))
         $resac = pg_query("insert into db_acount values($acount,227,1278,'$this->c01_dbabre','".pg_result($resaco,0,'c01_dbabre')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["c01_crabre"]))
         $resac = pg_query("insert into db_acount values($acount,227,1279,'$this->c01_crabre','".pg_result($resaco,0,'c01_crabre')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["c01_db01"]))
         $resac = pg_query("insert into db_acount values($acount,227,1280,'$this->c01_db01','".pg_result($resaco,0,'c01_db01')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["c01_cr01"]))
         $resac = pg_query("insert into db_acount values($acount,227,1281,'$this->c01_cr01','".pg_result($resaco,0,'c01_cr01')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["c01_db02"]))
         $resac = pg_query("insert into db_acount values($acount,227,1282,'$this->c01_db02','".pg_result($resaco,0,'c01_db02')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["c01_cr02"]))
         $resac = pg_query("insert into db_acount values($acount,227,1283,'$this->c01_cr02','".pg_result($resaco,0,'c01_cr02')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["c01_db03"]))
         $resac = pg_query("insert into db_acount values($acount,227,1284,'$this->c01_db03','".pg_result($resaco,0,'c01_db03')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["c01_cr03"]))
         $resac = pg_query("insert into db_acount values($acount,227,1285,'$this->c01_cr03','".pg_result($resaco,0,'c01_cr03')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["c01_db04"]))
         $resac = pg_query("insert into db_acount values($acount,227,1286,'$this->c01_db04','".pg_result($resaco,0,'c01_db04')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["c01_cr04"]))
         $resac = pg_query("insert into db_acount values($acount,227,1287,'$this->c01_cr04','".pg_result($resaco,0,'c01_cr04')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["c01_db05"]))
         $resac = pg_query("insert into db_acount values($acount,227,1288,'$this->c01_db05','".pg_result($resaco,0,'c01_db05')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["c01_cr05"]))
         $resac = pg_query("insert into db_acount values($acount,227,1289,'$this->c01_cr05','".pg_result($resaco,0,'c01_cr05')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["c01_db06"]))
         $resac = pg_query("insert into db_acount values($acount,227,1290,'$this->c01_db06','".pg_result($resaco,0,'c01_db06')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["c01_cr06"]))
         $resac = pg_query("insert into db_acount values($acount,227,1291,'$this->c01_cr06','".pg_result($resaco,0,'c01_cr06')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["c01_db07"]))
         $resac = pg_query("insert into db_acount values($acount,227,1292,'$this->c01_db07','".pg_result($resaco,0,'c01_db07')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["c01_cr07"]))
         $resac = pg_query("insert into db_acount values($acount,227,1293,'$this->c01_cr07','".pg_result($resaco,0,'c01_cr07')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["c01_db08"]))
         $resac = pg_query("insert into db_acount values($acount,227,1294,'$this->c01_db08','".pg_result($resaco,0,'c01_db08')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["c01_cr08"]))
         $resac = pg_query("insert into db_acount values($acount,227,1295,'$this->c01_cr08','".pg_result($resaco,0,'c01_cr08')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["c01_db09"]))
         $resac = pg_query("insert into db_acount values($acount,227,1296,'$this->c01_db09','".pg_result($resaco,0,'c01_db09')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["c01_cr09"]))
         $resac = pg_query("insert into db_acount values($acount,227,1297,'$this->c01_cr09','".pg_result($resaco,0,'c01_cr09')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["c01_db10"]))
         $resac = pg_query("insert into db_acount values($acount,227,1298,'$this->c01_db10','".pg_result($resaco,0,'c01_db10')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["c01_cr10"]))
         $resac = pg_query("insert into db_acount values($acount,227,1299,'$this->c01_cr10','".pg_result($resaco,0,'c01_cr10')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["c01_db11"]))
         $resac = pg_query("insert into db_acount values($acount,227,1300,'$this->c01_db11','".pg_result($resaco,0,'c01_db11')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["c01_cr11"]))
         $resac = pg_query("insert into db_acount values($acount,227,1301,'$this->c01_cr11','".pg_result($resaco,0,'c01_cr11')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["c01_db12"]))
         $resac = pg_query("insert into db_acount values($acount,227,1302,'$this->c01_db12','".pg_result($resaco,0,'c01_db12')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["c01_cr12"]))
         $resac = pg_query("insert into db_acount values($acount,227,1303,'$this->c01_cr12','".pg_result($resaco,0,'c01_cr12')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["c01_codtce"]))
         $resac = pg_query("insert into db_acount values($acount,227,1304,'$this->c01_codtce','".pg_result($resaco,0,'c01_codtce')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["c01_recurs"]))
         $resac = pg_query("insert into db_acount values($acount,227,1305,'$this->c01_recurs','".pg_result($resaco,0,'c01_recurs')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["c01_codbco"]))
         $resac = pg_query("insert into db_acount values($acount,227,1306,'$this->c01_codbco','".pg_result($resaco,0,'c01_codbco')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["c01_codage"]))
         $resac = pg_query("insert into db_acount values($acount,227,1307,'$this->c01_codage','".pg_result($resaco,0,'c01_codage')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["c01_codcta"]))
         $resac = pg_query("insert into db_acount values($acount,227,1308,'$this->c01_codcta','".pg_result($resaco,0,'c01_codcta')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["c01_tpcont"]))
         $resac = pg_query("insert into db_acount values($acount,227,1309,'$this->c01_tpcont','".pg_result($resaco,0,'c01_tpcont')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["c01_clarec"]))
         $resac = pg_query("insert into db_acount values($acount,227,1310,'$this->c01_clarec','".pg_result($resaco,0,'c01_clarec')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       pg_free_result($resaco);
     }
     $result = @pg_exec($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Plano de Contas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->c01_anousu."-".$this->c01_estrut;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Plano de Contas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->c01_anousu."-".$this->c01_estrut;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração Efetivada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c01_anousu."-".$this->c01_estrut;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($c01_anousu=null,$c01_estrut=null) { 
     $this->atualizacampos(true);
     $resaco = $this->sql_record($this->sql_query_file($this->c01_anousu,$this->c01_estrut));
     $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
     $acount = pg_result($resac,0,0);
     $resac = pg_query("insert into db_acountkey values($acount,1274,'".pg_result($resaco,$iresaco,'c01_anousu')."','E')");
     $resac = pg_query("insert into db_acountkey values($acount,1275,'".pg_result($resaco,$iresaco,'c01_estrut')."','E')");
     $resac = pg_query("insert into db_acount values($acount,227,1274,'','".pg_result($resaco,0,'c01_anousu')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1275,'','".pg_result($resaco,0,'c01_estrut')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1276,'','".pg_result($resaco,0,'c01_reduz')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1277,'','".pg_result($resaco,0,'c01_descr')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1278,'','".pg_result($resaco,0,'c01_dbabre')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1279,'','".pg_result($resaco,0,'c01_crabre')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1280,'','".pg_result($resaco,0,'c01_db01')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1281,'','".pg_result($resaco,0,'c01_cr01')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1282,'','".pg_result($resaco,0,'c01_db02')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1283,'','".pg_result($resaco,0,'c01_cr02')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1284,'','".pg_result($resaco,0,'c01_db03')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1285,'','".pg_result($resaco,0,'c01_cr03')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1286,'','".pg_result($resaco,0,'c01_db04')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1287,'','".pg_result($resaco,0,'c01_cr04')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1288,'','".pg_result($resaco,0,'c01_db05')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1289,'','".pg_result($resaco,0,'c01_cr05')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1290,'','".pg_result($resaco,0,'c01_db06')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1291,'','".pg_result($resaco,0,'c01_cr06')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1292,'','".pg_result($resaco,0,'c01_db07')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1293,'','".pg_result($resaco,0,'c01_cr07')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1294,'','".pg_result($resaco,0,'c01_db08')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1295,'','".pg_result($resaco,0,'c01_cr08')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1296,'','".pg_result($resaco,0,'c01_db09')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1297,'','".pg_result($resaco,0,'c01_cr09')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1298,'','".pg_result($resaco,0,'c01_db10')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1299,'','".pg_result($resaco,0,'c01_cr10')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1300,'','".pg_result($resaco,0,'c01_db11')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1301,'','".pg_result($resaco,0,'c01_cr11')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1302,'','".pg_result($resaco,0,'c01_db12')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1303,'','".pg_result($resaco,0,'c01_cr12')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1304,'','".pg_result($resaco,0,'c01_codtce')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1305,'','".pg_result($resaco,0,'c01_recurs')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1306,'','".pg_result($resaco,0,'c01_codbco')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1307,'','".pg_result($resaco,0,'c01_codage')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1308,'','".pg_result($resaco,0,'c01_codcta')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1309,'','".pg_result($resaco,0,'c01_tpcont')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,227,1310,'','".pg_result($resaco,0,'c01_clarec')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     pg_free_result($resaco);
     $sql = " delete from plano
                    where ";
     $sql2 = "";
      if($this->c01_anousu != ""){
      if($sql2!=""){
        $sql2 .= " and ";
      }
      $sql2 .= " c01_anousu = $this->c01_anousu ";
}
      if($this->c01_estrut != ""){
      if($sql2!=""){
        $sql2 .= " and ";
      }
      $sql2 .= " c01_estrut = '$this->c01_estrut' ";
}
     $result = @pg_exec($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Plano de Contas nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$this->c01_anousu."-".$this->c01_estrut;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Plano de Contas nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$this->c01_anousu."-".$this->c01_estrut;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão Efetivada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c01_anousu."-".$this->c01_estrut;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = @pg_query($sql);
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
        $this->erro_sql   = "Dados do Grupo nao Encontrado";
        $this->erro_msg   = "Usuário: \n\n ".$this->erro_sql." \n\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $c01_anousu=null,$c01_estrut=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from plano ";
     $sql2 = "";
     if($dbwhere==""){
       if($c01_anousu!=null ){
         $sql2 .= " where plano.c01_anousu = $c01_anousu "; 
       } 
       if($c01_estrut!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " plano.c01_estrut = '$c01_estrut' "; 
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
   function sql_query_file ( $c01_anousu=null,$c01_estrut=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from plano ";
     $sql2 = "";
     if($dbwhere==""){
       if($c01_anousu!=null ){
         $sql2 .= " where plano.c01_anousu = $c01_anousu "; 
       } 
       if($c01_estrut!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " plano.c01_estrut = '$c01_estrut' "; 
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
}
?>