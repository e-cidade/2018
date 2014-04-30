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

//MODULO: orcamento
//CLASSE DA ENTIDADE orctiporecconvenio
class cl_orctiporecconvenio { 
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
   var $o16_sequencial = 0; 
   var $o16_orctiporec = 0; 
   var $o16_percentual = 0; 
   var $o16_dtassinatura_dia = null; 
   var $o16_dtassinatura_mes = null; 
   var $o16_dtassinatura_ano = null; 
   var $o16_dtassinatura = null; 
   var $o16_dtvigenciaini_dia = null; 
   var $o16_dtvigenciaini_mes = null; 
   var $o16_dtvigenciaini_ano = null; 
   var $o16_dtvigenciaini = null; 
   var $o16_dtvigenciafim_dia = null; 
   var $o16_dtvigenciafim_mes = null; 
   var $o16_dtvigenciafim_ano = null; 
   var $o16_dtvigenciafim = null; 
   var $o16_dtprestacaoini_dia = null; 
   var $o16_dtprestacaoini_mes = null; 
   var $o16_dtprestacaoini_ano = null; 
   var $o16_dtprestacaoini = null; 
   var $o16_dtprestacaofim_dia = null; 
   var $o16_dtprestacaofim_mes = null; 
   var $o16_dtprestacaofim_ano = null; 
   var $o16_dtprestacaofim = null; 
   var $o16_dtprorrogacaoini_dia = null; 
   var $o16_dtprorrogacaoini_mes = null; 
   var $o16_dtprorrogacaoini_ano = null; 
   var $o16_dtprorrogacaoini = null; 
   var $o16_dtprorrogacaofim_dia = null; 
   var $o16_dtprorrogacaofim_mes = null; 
   var $o16_dtprorrogacaofim_ano = null; 
   var $o16_dtprorrogacaofim = null; 
   var $o16_convenio = null; 
   var $o16_observacao = null; 
   var $o16_objeto = null; 
   var $o16_valor = 0; 
   var $o16_saldoaberturacp = 0; 
   var $o16_saldoabertura = 0; 
   var $o16_saltes = 0; 
   var $o16_tipopacto = 0; 
   var $o16_orctiporecconveniosituacao = 0; 
   var $o16_concedente = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o16_sequencial = int4 = Código Sequencial 
                 o16_orctiporec = int4 = Recurso 
                 o16_percentual = float4 = Percentual de Participação 
                 o16_dtassinatura = date = Data de Assinatura 
                 o16_dtvigenciaini = date = Data de Vigência 
                 o16_dtvigenciafim = date = Data de Vigência Final 
                 o16_dtprestacaoini = date = Data de Prestação de Contas 
                 o16_dtprestacaofim = date = Data de Prestação de Contas Final 
                 o16_dtprorrogacaoini = date = Prorrogação do Convênio 
                 o16_dtprorrogacaofim = date = Prorrogação do Convênio Final 
                 o16_convenio = varchar(50) = Número do Convênio 
                 o16_observacao = text = Observações 
                 o16_objeto = text = Objeto do Convênio 
                 o16_valor = float8 = Valor 
                 o16_saldoaberturacp = float4 = Saldo de abertura contra-partida 
                 o16_saldoabertura = float4 = Saldo de abertura 
                 o16_saltes = int4 = Código Conta 
                 o16_tipopacto = int4 = Codigo tipo de pacto 
                 o16_orctiporecconveniosituacao = int4 = Sequencial 
                 o16_concedente = int4 = Sequencial 
                 ";
   //funcao construtor da classe 
   function cl_orctiporecconvenio() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orctiporecconvenio"); 
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
       $this->o16_sequencial = ($this->o16_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o16_sequencial"]:$this->o16_sequencial);
       $this->o16_orctiporec = ($this->o16_orctiporec == ""?@$GLOBALS["HTTP_POST_VARS"]["o16_orctiporec"]:$this->o16_orctiporec);
       $this->o16_percentual = ($this->o16_percentual == ""?@$GLOBALS["HTTP_POST_VARS"]["o16_percentual"]:$this->o16_percentual);
       if($this->o16_dtassinatura == ""){
         $this->o16_dtassinatura_dia = ($this->o16_dtassinatura_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["o16_dtassinatura_dia"]:$this->o16_dtassinatura_dia);
         $this->o16_dtassinatura_mes = ($this->o16_dtassinatura_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["o16_dtassinatura_mes"]:$this->o16_dtassinatura_mes);
         $this->o16_dtassinatura_ano = ($this->o16_dtassinatura_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["o16_dtassinatura_ano"]:$this->o16_dtassinatura_ano);
         if($this->o16_dtassinatura_dia != ""){
            $this->o16_dtassinatura = $this->o16_dtassinatura_ano."-".$this->o16_dtassinatura_mes."-".$this->o16_dtassinatura_dia;
         }
       }
       if($this->o16_dtvigenciaini == ""){
         $this->o16_dtvigenciaini_dia = ($this->o16_dtvigenciaini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["o16_dtvigenciaini_dia"]:$this->o16_dtvigenciaini_dia);
         $this->o16_dtvigenciaini_mes = ($this->o16_dtvigenciaini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["o16_dtvigenciaini_mes"]:$this->o16_dtvigenciaini_mes);
         $this->o16_dtvigenciaini_ano = ($this->o16_dtvigenciaini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["o16_dtvigenciaini_ano"]:$this->o16_dtvigenciaini_ano);
         if($this->o16_dtvigenciaini_dia != ""){
            $this->o16_dtvigenciaini = $this->o16_dtvigenciaini_ano."-".$this->o16_dtvigenciaini_mes."-".$this->o16_dtvigenciaini_dia;
         }
       }
       if($this->o16_dtvigenciafim == ""){
         $this->o16_dtvigenciafim_dia = ($this->o16_dtvigenciafim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["o16_dtvigenciafim_dia"]:$this->o16_dtvigenciafim_dia);
         $this->o16_dtvigenciafim_mes = ($this->o16_dtvigenciafim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["o16_dtvigenciafim_mes"]:$this->o16_dtvigenciafim_mes);
         $this->o16_dtvigenciafim_ano = ($this->o16_dtvigenciafim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["o16_dtvigenciafim_ano"]:$this->o16_dtvigenciafim_ano);
         if($this->o16_dtvigenciafim_dia != ""){
            $this->o16_dtvigenciafim = $this->o16_dtvigenciafim_ano."-".$this->o16_dtvigenciafim_mes."-".$this->o16_dtvigenciafim_dia;
         }
       }
       if($this->o16_dtprestacaoini == ""){
         $this->o16_dtprestacaoini_dia = ($this->o16_dtprestacaoini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["o16_dtprestacaoini_dia"]:$this->o16_dtprestacaoini_dia);
         $this->o16_dtprestacaoini_mes = ($this->o16_dtprestacaoini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["o16_dtprestacaoini_mes"]:$this->o16_dtprestacaoini_mes);
         $this->o16_dtprestacaoini_ano = ($this->o16_dtprestacaoini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["o16_dtprestacaoini_ano"]:$this->o16_dtprestacaoini_ano);
         if($this->o16_dtprestacaoini_dia != ""){
            $this->o16_dtprestacaoini = $this->o16_dtprestacaoini_ano."-".$this->o16_dtprestacaoini_mes."-".$this->o16_dtprestacaoini_dia;
         }
       }
       if($this->o16_dtprestacaofim == ""){
         $this->o16_dtprestacaofim_dia = ($this->o16_dtprestacaofim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["o16_dtprestacaofim_dia"]:$this->o16_dtprestacaofim_dia);
         $this->o16_dtprestacaofim_mes = ($this->o16_dtprestacaofim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["o16_dtprestacaofim_mes"]:$this->o16_dtprestacaofim_mes);
         $this->o16_dtprestacaofim_ano = ($this->o16_dtprestacaofim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["o16_dtprestacaofim_ano"]:$this->o16_dtprestacaofim_ano);
         if($this->o16_dtprestacaofim_dia != ""){
            $this->o16_dtprestacaofim = $this->o16_dtprestacaofim_ano."-".$this->o16_dtprestacaofim_mes."-".$this->o16_dtprestacaofim_dia;
         }
       }
       if($this->o16_dtprorrogacaoini == ""){
         $this->o16_dtprorrogacaoini_dia = ($this->o16_dtprorrogacaoini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["o16_dtprorrogacaoini_dia"]:$this->o16_dtprorrogacaoini_dia);
         $this->o16_dtprorrogacaoini_mes = ($this->o16_dtprorrogacaoini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["o16_dtprorrogacaoini_mes"]:$this->o16_dtprorrogacaoini_mes);
         $this->o16_dtprorrogacaoini_ano = ($this->o16_dtprorrogacaoini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["o16_dtprorrogacaoini_ano"]:$this->o16_dtprorrogacaoini_ano);
         if($this->o16_dtprorrogacaoini_dia != ""){
            $this->o16_dtprorrogacaoini = $this->o16_dtprorrogacaoini_ano."-".$this->o16_dtprorrogacaoini_mes."-".$this->o16_dtprorrogacaoini_dia;
         }
       }
       if($this->o16_dtprorrogacaofim == ""){
         $this->o16_dtprorrogacaofim_dia = ($this->o16_dtprorrogacaofim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["o16_dtprorrogacaofim_dia"]:$this->o16_dtprorrogacaofim_dia);
         $this->o16_dtprorrogacaofim_mes = ($this->o16_dtprorrogacaofim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["o16_dtprorrogacaofim_mes"]:$this->o16_dtprorrogacaofim_mes);
         $this->o16_dtprorrogacaofim_ano = ($this->o16_dtprorrogacaofim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["o16_dtprorrogacaofim_ano"]:$this->o16_dtprorrogacaofim_ano);
         if($this->o16_dtprorrogacaofim_dia != ""){
            $this->o16_dtprorrogacaofim = $this->o16_dtprorrogacaofim_ano."-".$this->o16_dtprorrogacaofim_mes."-".$this->o16_dtprorrogacaofim_dia;
         }
       }
       $this->o16_convenio = ($this->o16_convenio == ""?@$GLOBALS["HTTP_POST_VARS"]["o16_convenio"]:$this->o16_convenio);
       $this->o16_observacao = ($this->o16_observacao == ""?@$GLOBALS["HTTP_POST_VARS"]["o16_observacao"]:$this->o16_observacao);
       $this->o16_objeto = ($this->o16_objeto == ""?@$GLOBALS["HTTP_POST_VARS"]["o16_objeto"]:$this->o16_objeto);
       $this->o16_valor = ($this->o16_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["o16_valor"]:$this->o16_valor);
       $this->o16_saldoaberturacp = ($this->o16_saldoaberturacp == ""?@$GLOBALS["HTTP_POST_VARS"]["o16_saldoaberturacp"]:$this->o16_saldoaberturacp);
       $this->o16_saldoabertura = ($this->o16_saldoabertura == ""?@$GLOBALS["HTTP_POST_VARS"]["o16_saldoabertura"]:$this->o16_saldoabertura);
       $this->o16_saltes = ($this->o16_saltes == ""?@$GLOBALS["HTTP_POST_VARS"]["o16_saltes"]:$this->o16_saltes);
       $this->o16_tipopacto = ($this->o16_tipopacto == ""?@$GLOBALS["HTTP_POST_VARS"]["o16_tipopacto"]:$this->o16_tipopacto);
       $this->o16_orctiporecconveniosituacao = ($this->o16_orctiporecconveniosituacao == ""?@$GLOBALS["HTTP_POST_VARS"]["o16_orctiporecconveniosituacao"]:$this->o16_orctiporecconveniosituacao);
       $this->o16_concedente = ($this->o16_concedente == ""?@$GLOBALS["HTTP_POST_VARS"]["o16_concedente"]:$this->o16_concedente);
     }else{
       $this->o16_sequencial = ($this->o16_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o16_sequencial"]:$this->o16_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($o16_sequencial){ 
      $this->atualizacampos();
     if($this->o16_orctiporec == null ){ 
       $this->erro_sql = " Campo Recurso nao Informado.";
       $this->erro_campo = "o16_orctiporec";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o16_percentual == null ){ 
       $this->o16_percentual = "0";
     }
     if($this->o16_dtassinatura == null ){ 
       $this->o16_dtassinatura = "null";
     }
     if($this->o16_dtvigenciaini == null ){ 
       $this->o16_dtvigenciaini = "null";
     }
     if($this->o16_dtvigenciafim == null ){ 
       $this->o16_dtvigenciafim = "null";
     }
     if($this->o16_dtprestacaoini == null ){ 
       $this->o16_dtprestacaoini = "null";
     }
     if($this->o16_dtprestacaofim == null ){ 
       $this->o16_dtprestacaofim = "null";
     }
     if($this->o16_dtprorrogacaoini == null ){ 
       $this->o16_dtprorrogacaoini = "null";
     }
     if($this->o16_dtprorrogacaofim == null ){ 
       $this->o16_dtprorrogacaofim = "null";
     }
     if($this->o16_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "o16_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o16_saldoaberturacp == null ){ 
       $this->erro_sql = " Campo Saldo de abertura contra-partida nao Informado.";
       $this->erro_campo = "o16_saldoaberturacp";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o16_saldoabertura == null ){ 
       $this->erro_sql = " Campo Saldo de abertura nao Informado.";
       $this->erro_campo = "o16_saldoabertura";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o16_saltes == null ){ 
       $this->erro_sql = " Campo Código Conta nao Informado.";
       $this->erro_campo = "o16_saltes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o16_tipopacto == null ){ 
       $this->erro_sql = " Campo Codigo tipo de pacto nao Informado.";
       $this->erro_campo = "o16_tipopacto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o16_orctiporecconveniosituacao == null ){ 
       $this->erro_sql = " Campo Sequencial nao Informado.";
       $this->erro_campo = "o16_orctiporecconveniosituacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o16_concedente == null ){ 
       $this->erro_sql = " Campo Sequencial nao Informado.";
       $this->erro_campo = "o16_concedente";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($o16_sequencial == "" || $o16_sequencial == null ){
       $result = db_query("select nextval('orctiporecconvenio_o16_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: orctiporecconvenio_o16_sequencial_seq do campo: o16_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->o16_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from orctiporecconvenio_o16_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $o16_sequencial)){
         $this->erro_sql = " Campo o16_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->o16_sequencial = $o16_sequencial; 
       }
     }
     if(($this->o16_sequencial == null) || ($this->o16_sequencial == "") ){ 
       $this->erro_sql = " Campo o16_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orctiporecconvenio(
                                       o16_sequencial 
                                      ,o16_orctiporec 
                                      ,o16_percentual 
                                      ,o16_dtassinatura 
                                      ,o16_dtvigenciaini 
                                      ,o16_dtvigenciafim 
                                      ,o16_dtprestacaoini 
                                      ,o16_dtprestacaofim 
                                      ,o16_dtprorrogacaoini 
                                      ,o16_dtprorrogacaofim 
                                      ,o16_convenio 
                                      ,o16_observacao 
                                      ,o16_objeto 
                                      ,o16_valor 
                                      ,o16_saldoaberturacp 
                                      ,o16_saldoabertura 
                                      ,o16_saltes 
                                      ,o16_tipopacto 
                                      ,o16_orctiporecconveniosituacao 
                                      ,o16_concedente 
                       )
                values (
                                $this->o16_sequencial 
                               ,$this->o16_orctiporec 
                               ,$this->o16_percentual 
                               ,".($this->o16_dtassinatura == "null" || $this->o16_dtassinatura == ""?"null":"'".$this->o16_dtassinatura."'")." 
                               ,".($this->o16_dtvigenciaini == "null" || $this->o16_dtvigenciaini == ""?"null":"'".$this->o16_dtvigenciaini."'")." 
                               ,".($this->o16_dtvigenciafim == "null" || $this->o16_dtvigenciafim == ""?"null":"'".$this->o16_dtvigenciafim."'")." 
                               ,".($this->o16_dtprestacaoini == "null" || $this->o16_dtprestacaoini == ""?"null":"'".$this->o16_dtprestacaoini."'")." 
                               ,".($this->o16_dtprestacaofim == "null" || $this->o16_dtprestacaofim == ""?"null":"'".$this->o16_dtprestacaofim."'")." 
                               ,".($this->o16_dtprorrogacaoini == "null" || $this->o16_dtprorrogacaoini == ""?"null":"'".$this->o16_dtprorrogacaoini."'")." 
                               ,".($this->o16_dtprorrogacaofim == "null" || $this->o16_dtprorrogacaofim == ""?"null":"'".$this->o16_dtprorrogacaofim."'")." 
                               ,'$this->o16_convenio' 
                               ,'$this->o16_observacao' 
                               ,'$this->o16_objeto' 
                               ,$this->o16_valor 
                               ,$this->o16_saldoaberturacp 
                               ,$this->o16_saldoabertura 
                               ,$this->o16_saltes 
                               ,$this->o16_tipopacto 
                               ,$this->o16_orctiporecconveniosituacao 
                               ,$this->o16_concedente 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Convenio do Recurso ($this->o16_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Convenio do Recurso já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Convenio do Recurso ($this->o16_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o16_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o16_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,11899,'$this->o16_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2058,11899,'','".AddSlashes(pg_result($resaco,0,'o16_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2058,11900,'','".AddSlashes(pg_result($resaco,0,'o16_orctiporec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2058,11901,'','".AddSlashes(pg_result($resaco,0,'o16_percentual'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2058,11902,'','".AddSlashes(pg_result($resaco,0,'o16_dtassinatura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2058,11903,'','".AddSlashes(pg_result($resaco,0,'o16_dtvigenciaini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2058,11904,'','".AddSlashes(pg_result($resaco,0,'o16_dtvigenciafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2058,11905,'','".AddSlashes(pg_result($resaco,0,'o16_dtprestacaoini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2058,11906,'','".AddSlashes(pg_result($resaco,0,'o16_dtprestacaofim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2058,11907,'','".AddSlashes(pg_result($resaco,0,'o16_dtprorrogacaoini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2058,11908,'','".AddSlashes(pg_result($resaco,0,'o16_dtprorrogacaofim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2058,11909,'','".AddSlashes(pg_result($resaco,0,'o16_convenio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2058,11911,'','".AddSlashes(pg_result($resaco,0,'o16_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2058,11910,'','".AddSlashes(pg_result($resaco,0,'o16_objeto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2058,3547,'','".AddSlashes(pg_result($resaco,0,'o16_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2058,13955,'','".AddSlashes(pg_result($resaco,0,'o16_saldoaberturacp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2058,13954,'','".AddSlashes(pg_result($resaco,0,'o16_saldoabertura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2058,13953,'','".AddSlashes(pg_result($resaco,0,'o16_saltes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2058,13952,'','".AddSlashes(pg_result($resaco,0,'o16_tipopacto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2058,13951,'','".AddSlashes(pg_result($resaco,0,'o16_orctiporecconveniosituacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2058,13950,'','".AddSlashes(pg_result($resaco,0,'o16_concedente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o16_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update orctiporecconvenio set ";
     $virgula = "";
     if(trim($this->o16_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o16_sequencial"])){ 
       $sql  .= $virgula." o16_sequencial = $this->o16_sequencial ";
       $virgula = ",";
       if(trim($this->o16_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "o16_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o16_orctiporec)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o16_orctiporec"])){ 
       $sql  .= $virgula." o16_orctiporec = $this->o16_orctiporec ";
       $virgula = ",";
       if(trim($this->o16_orctiporec) == null ){ 
         $this->erro_sql = " Campo Recurso nao Informado.";
         $this->erro_campo = "o16_orctiporec";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o16_percentual)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o16_percentual"])){ 
        if(trim($this->o16_percentual)=="" && isset($GLOBALS["HTTP_POST_VARS"]["o16_percentual"])){ 
           $this->o16_percentual = "0" ; 
        } 
       $sql  .= $virgula." o16_percentual = $this->o16_percentual ";
       $virgula = ",";
     }
     if(trim($this->o16_dtassinatura)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o16_dtassinatura_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["o16_dtassinatura_dia"] !="") ){ 
       $sql  .= $virgula." o16_dtassinatura = '$this->o16_dtassinatura' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["o16_dtassinatura_dia"])){ 
         $sql  .= $virgula." o16_dtassinatura = null ";
         $virgula = ",";
       }
     }
     if(trim($this->o16_dtvigenciaini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o16_dtvigenciaini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["o16_dtvigenciaini_dia"] !="") ){ 
       $sql  .= $virgula." o16_dtvigenciaini = '$this->o16_dtvigenciaini' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["o16_dtvigenciaini_dia"])){ 
         $sql  .= $virgula." o16_dtvigenciaini = null ";
         $virgula = ",";
       }
     }
     if(trim($this->o16_dtvigenciafim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o16_dtvigenciafim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["o16_dtvigenciafim_dia"] !="") ){ 
       $sql  .= $virgula." o16_dtvigenciafim = '$this->o16_dtvigenciafim' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["o16_dtvigenciafim_dia"])){ 
         $sql  .= $virgula." o16_dtvigenciafim = null ";
         $virgula = ",";
       }
     }
     if(trim($this->o16_dtprestacaoini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o16_dtprestacaoini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["o16_dtprestacaoini_dia"] !="") ){ 
       $sql  .= $virgula." o16_dtprestacaoini = '$this->o16_dtprestacaoini' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["o16_dtprestacaoini_dia"])){ 
         $sql  .= $virgula." o16_dtprestacaoini = null ";
         $virgula = ",";
       }
     }
     if(trim($this->o16_dtprestacaofim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o16_dtprestacaofim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["o16_dtprestacaofim_dia"] !="") ){ 
       $sql  .= $virgula." o16_dtprestacaofim = '$this->o16_dtprestacaofim' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["o16_dtprestacaofim_dia"])){ 
         $sql  .= $virgula." o16_dtprestacaofim = null ";
         $virgula = ",";
       }
     }
     if(trim($this->o16_dtprorrogacaoini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o16_dtprorrogacaoini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["o16_dtprorrogacaoini_dia"] !="") ){ 
       $sql  .= $virgula." o16_dtprorrogacaoini = '$this->o16_dtprorrogacaoini' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["o16_dtprorrogacaoini_dia"])){ 
         $sql  .= $virgula." o16_dtprorrogacaoini = null ";
         $virgula = ",";
       }
     }
     if(trim($this->o16_dtprorrogacaofim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o16_dtprorrogacaofim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["o16_dtprorrogacaofim_dia"] !="") ){ 
       $sql  .= $virgula." o16_dtprorrogacaofim = '$this->o16_dtprorrogacaofim' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["o16_dtprorrogacaofim_dia"])){ 
         $sql  .= $virgula." o16_dtprorrogacaofim = null ";
         $virgula = ",";
       }
     }
     if(trim($this->o16_convenio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o16_convenio"])){ 
       $sql  .= $virgula." o16_convenio = '$this->o16_convenio' ";
       $virgula = ",";
     }
     if(trim($this->o16_observacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o16_observacao"])){ 
       $sql  .= $virgula." o16_observacao = '$this->o16_observacao' ";
       $virgula = ",";
     }
     if(trim($this->o16_objeto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o16_objeto"])){ 
       $sql  .= $virgula." o16_objeto = '$this->o16_objeto' ";
       $virgula = ",";
     }
     if(trim($this->o16_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o16_valor"])){ 
       $sql  .= $virgula." o16_valor = $this->o16_valor ";
       $virgula = ",";
       if(trim($this->o16_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "o16_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o16_saldoaberturacp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o16_saldoaberturacp"])){ 
       $sql  .= $virgula." o16_saldoaberturacp = $this->o16_saldoaberturacp ";
       $virgula = ",";
       if(trim($this->o16_saldoaberturacp) == null ){ 
         $this->erro_sql = " Campo Saldo de abertura contra-partida nao Informado.";
         $this->erro_campo = "o16_saldoaberturacp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o16_saldoabertura)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o16_saldoabertura"])){ 
       $sql  .= $virgula." o16_saldoabertura = $this->o16_saldoabertura ";
       $virgula = ",";
       if(trim($this->o16_saldoabertura) == null ){ 
         $this->erro_sql = " Campo Saldo de abertura nao Informado.";
         $this->erro_campo = "o16_saldoabertura";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o16_saltes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o16_saltes"])){ 
       $sql  .= $virgula." o16_saltes = $this->o16_saltes ";
       $virgula = ",";
       if(trim($this->o16_saltes) == null ){ 
         $this->erro_sql = " Campo Código Conta nao Informado.";
         $this->erro_campo = "o16_saltes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o16_tipopacto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o16_tipopacto"])){ 
       $sql  .= $virgula." o16_tipopacto = $this->o16_tipopacto ";
       $virgula = ",";
       if(trim($this->o16_tipopacto) == null ){ 
         $this->erro_sql = " Campo Codigo tipo de pacto nao Informado.";
         $this->erro_campo = "o16_tipopacto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o16_orctiporecconveniosituacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o16_orctiporecconveniosituacao"])){ 
       $sql  .= $virgula." o16_orctiporecconveniosituacao = $this->o16_orctiporecconveniosituacao ";
       $virgula = ",";
       if(trim($this->o16_orctiporecconveniosituacao) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "o16_orctiporecconveniosituacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o16_concedente)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o16_concedente"])){ 
       $sql  .= $virgula." o16_concedente = $this->o16_concedente ";
       $virgula = ",";
       if(trim($this->o16_concedente) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "o16_concedente";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($o16_sequencial!=null){
       $sql .= " o16_sequencial = $this->o16_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o16_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11899,'$this->o16_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o16_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,2058,11899,'".AddSlashes(pg_result($resaco,$conresaco,'o16_sequencial'))."','$this->o16_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o16_orctiporec"]))
           $resac = db_query("insert into db_acount values($acount,2058,11900,'".AddSlashes(pg_result($resaco,$conresaco,'o16_orctiporec'))."','$this->o16_orctiporec',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o16_percentual"]))
           $resac = db_query("insert into db_acount values($acount,2058,11901,'".AddSlashes(pg_result($resaco,$conresaco,'o16_percentual'))."','$this->o16_percentual',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o16_dtassinatura"]))
           $resac = db_query("insert into db_acount values($acount,2058,11902,'".AddSlashes(pg_result($resaco,$conresaco,'o16_dtassinatura'))."','$this->o16_dtassinatura',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o16_dtvigenciaini"]))
           $resac = db_query("insert into db_acount values($acount,2058,11903,'".AddSlashes(pg_result($resaco,$conresaco,'o16_dtvigenciaini'))."','$this->o16_dtvigenciaini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o16_dtvigenciafim"]))
           $resac = db_query("insert into db_acount values($acount,2058,11904,'".AddSlashes(pg_result($resaco,$conresaco,'o16_dtvigenciafim'))."','$this->o16_dtvigenciafim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o16_dtprestacaoini"]))
           $resac = db_query("insert into db_acount values($acount,2058,11905,'".AddSlashes(pg_result($resaco,$conresaco,'o16_dtprestacaoini'))."','$this->o16_dtprestacaoini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o16_dtprestacaofim"]))
           $resac = db_query("insert into db_acount values($acount,2058,11906,'".AddSlashes(pg_result($resaco,$conresaco,'o16_dtprestacaofim'))."','$this->o16_dtprestacaofim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o16_dtprorrogacaoini"]))
           $resac = db_query("insert into db_acount values($acount,2058,11907,'".AddSlashes(pg_result($resaco,$conresaco,'o16_dtprorrogacaoini'))."','$this->o16_dtprorrogacaoini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o16_dtprorrogacaofim"]))
           $resac = db_query("insert into db_acount values($acount,2058,11908,'".AddSlashes(pg_result($resaco,$conresaco,'o16_dtprorrogacaofim'))."','$this->o16_dtprorrogacaofim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o16_convenio"]))
           $resac = db_query("insert into db_acount values($acount,2058,11909,'".AddSlashes(pg_result($resaco,$conresaco,'o16_convenio'))."','$this->o16_convenio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o16_observacao"]))
           $resac = db_query("insert into db_acount values($acount,2058,11911,'".AddSlashes(pg_result($resaco,$conresaco,'o16_observacao'))."','$this->o16_observacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o16_objeto"]))
           $resac = db_query("insert into db_acount values($acount,2058,11910,'".AddSlashes(pg_result($resaco,$conresaco,'o16_objeto'))."','$this->o16_objeto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o16_valor"]))
           $resac = db_query("insert into db_acount values($acount,2058,3547,'".AddSlashes(pg_result($resaco,$conresaco,'o16_valor'))."','$this->o16_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o16_saldoaberturacp"]))
           $resac = db_query("insert into db_acount values($acount,2058,13955,'".AddSlashes(pg_result($resaco,$conresaco,'o16_saldoaberturacp'))."','$this->o16_saldoaberturacp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o16_saldoabertura"]))
           $resac = db_query("insert into db_acount values($acount,2058,13954,'".AddSlashes(pg_result($resaco,$conresaco,'o16_saldoabertura'))."','$this->o16_saldoabertura',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o16_saltes"]))
           $resac = db_query("insert into db_acount values($acount,2058,13953,'".AddSlashes(pg_result($resaco,$conresaco,'o16_saltes'))."','$this->o16_saltes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o16_tipopacto"]))
           $resac = db_query("insert into db_acount values($acount,2058,13952,'".AddSlashes(pg_result($resaco,$conresaco,'o16_tipopacto'))."','$this->o16_tipopacto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o16_orctiporecconveniosituacao"]))
           $resac = db_query("insert into db_acount values($acount,2058,13951,'".AddSlashes(pg_result($resaco,$conresaco,'o16_orctiporecconveniosituacao'))."','$this->o16_orctiporecconveniosituacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o16_concedente"]))
           $resac = db_query("insert into db_acount values($acount,2058,13950,'".AddSlashes(pg_result($resaco,$conresaco,'o16_concedente'))."','$this->o16_concedente',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Convenio do Recurso nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o16_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Convenio do Recurso nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o16_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o16_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o16_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o16_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11899,'$o16_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2058,11899,'','".AddSlashes(pg_result($resaco,$iresaco,'o16_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2058,11900,'','".AddSlashes(pg_result($resaco,$iresaco,'o16_orctiporec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2058,11901,'','".AddSlashes(pg_result($resaco,$iresaco,'o16_percentual'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2058,11902,'','".AddSlashes(pg_result($resaco,$iresaco,'o16_dtassinatura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2058,11903,'','".AddSlashes(pg_result($resaco,$iresaco,'o16_dtvigenciaini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2058,11904,'','".AddSlashes(pg_result($resaco,$iresaco,'o16_dtvigenciafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2058,11905,'','".AddSlashes(pg_result($resaco,$iresaco,'o16_dtprestacaoini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2058,11906,'','".AddSlashes(pg_result($resaco,$iresaco,'o16_dtprestacaofim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2058,11907,'','".AddSlashes(pg_result($resaco,$iresaco,'o16_dtprorrogacaoini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2058,11908,'','".AddSlashes(pg_result($resaco,$iresaco,'o16_dtprorrogacaofim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2058,11909,'','".AddSlashes(pg_result($resaco,$iresaco,'o16_convenio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2058,11911,'','".AddSlashes(pg_result($resaco,$iresaco,'o16_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2058,11910,'','".AddSlashes(pg_result($resaco,$iresaco,'o16_objeto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2058,3547,'','".AddSlashes(pg_result($resaco,$iresaco,'o16_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2058,13955,'','".AddSlashes(pg_result($resaco,$iresaco,'o16_saldoaberturacp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2058,13954,'','".AddSlashes(pg_result($resaco,$iresaco,'o16_saldoabertura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2058,13953,'','".AddSlashes(pg_result($resaco,$iresaco,'o16_saltes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2058,13952,'','".AddSlashes(pg_result($resaco,$iresaco,'o16_tipopacto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2058,13951,'','".AddSlashes(pg_result($resaco,$iresaco,'o16_orctiporecconveniosituacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2058,13950,'','".AddSlashes(pg_result($resaco,$iresaco,'o16_concedente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from orctiporecconvenio
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o16_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o16_sequencial = $o16_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Convenio do Recurso nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o16_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Convenio do Recurso nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o16_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o16_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:orctiporecconvenio";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $o16_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orctiporecconvenio ";
     $sql .= "      inner join saltes  on  saltes.k13_conta = orctiporecconvenio.o16_saltes";
     $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = orctiporecconvenio.o16_orctiporec";
     $sql .= "      inner join tipopacto  on  tipopacto.o29_sequencial = orctiporecconvenio.o16_tipopacto";
     $sql .= "      inner join orctiporecconveniosituacao  on  orctiporecconveniosituacao.o100_sequencial = orctiporecconvenio.o16_orctiporecconveniosituacao";
     $sql .= "      inner join concedente  on  concedente.o108_sequencial = orctiporecconvenio.o16_concedente";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = concedente.o108_numcgm";
     $sql .= "      inner join tipoconcedente  on  tipoconcedente.o37_sequencial = concedente.o108_tipoconcedente";
     $sql2 = "";
     if($dbwhere==""){
       if($o16_sequencial!=null ){
         $sql2 .= " where orctiporecconvenio.o16_sequencial = $o16_sequencial "; 
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
   function sql_query_file ( $o16_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orctiporecconvenio ";
     $sql2 = "";
     if($dbwhere==""){
       if($o16_sequencial!=null ){
         $sql2 .= " where orctiporecconvenio.o16_sequencial = $o16_sequencial "; 
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