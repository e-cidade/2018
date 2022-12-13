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

//MODULO: Caixa
//CLASSE DA ENTIDADE recibopaga
class cl_recibopaga {
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
   var $k00_numcgm = 0;
   var $k00_dtoper_dia = null;
   var $k00_dtoper_mes = null;
   var $k00_dtoper_ano = null;
   var $k00_dtoper = null;
   var $k00_receit = 0;
   var $k00_hist = 0;
   var $k00_valor = 0;
   var $k00_dtvenc_dia = null;
   var $k00_dtvenc_mes = null;
   var $k00_dtvenc_ano = null;
   var $k00_dtvenc = null;
   var $k00_numpre = 0;
   var $k00_numpar = 0;
   var $k00_numtot = 0;
   var $k00_numdig = 0;
   var $k00_conta = 0;
   var $k00_dtpaga_dia = null;
   var $k00_dtpaga_mes = null;
   var $k00_dtpaga_ano = null;
   var $k00_dtpaga = null;
   var $k00_numnov = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 k00_numcgm = int4 = cgm
                 k00_dtoper = date = DT.Lanc
                 k00_receit = int4 = Receita
                 k00_hist = int4 = Histórico de Cálculo
                 k00_valor = float8 = Valor
                 k00_dtvenc = date = DT.Venc
                 k00_numpre = int4 = Numpre
                 k00_numpar = int4 = Parcela
                 k00_numtot = int4 = Tot
                 k00_numdig = int4 = D
                 k00_conta = int4 = Conta
                 k00_dtpaga = date = Data do pagamento
                 k00_numnov = int4 = Codigo Auxiliar
                 ";
   //funcao construtor da classe
   function cl_recibopaga() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("recibopaga");
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
       $this->k00_numcgm = ($this->k00_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_numcgm"]:$this->k00_numcgm);
       if($this->k00_dtoper == ""){
         $this->k00_dtoper_dia = ($this->k00_dtoper_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_dtoper_dia"]:$this->k00_dtoper_dia);
         $this->k00_dtoper_mes = ($this->k00_dtoper_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_dtoper_mes"]:$this->k00_dtoper_mes);
         $this->k00_dtoper_ano = ($this->k00_dtoper_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_dtoper_ano"]:$this->k00_dtoper_ano);
         if($this->k00_dtoper_dia != ""){
            $this->k00_dtoper = $this->k00_dtoper_ano."-".$this->k00_dtoper_mes."-".$this->k00_dtoper_dia;
         }
       }
       $this->k00_receit = ($this->k00_receit == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_receit"]:$this->k00_receit);
       $this->k00_hist = ($this->k00_hist == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_hist"]:$this->k00_hist);
       $this->k00_valor = ($this->k00_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_valor"]:$this->k00_valor);
       if($this->k00_dtvenc == ""){
         $this->k00_dtvenc_dia = ($this->k00_dtvenc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_dtvenc_dia"]:$this->k00_dtvenc_dia);
         $this->k00_dtvenc_mes = ($this->k00_dtvenc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_dtvenc_mes"]:$this->k00_dtvenc_mes);
         $this->k00_dtvenc_ano = ($this->k00_dtvenc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_dtvenc_ano"]:$this->k00_dtvenc_ano);
         if($this->k00_dtvenc_dia != ""){
            $this->k00_dtvenc = $this->k00_dtvenc_ano."-".$this->k00_dtvenc_mes."-".$this->k00_dtvenc_dia;
         }
       }
       $this->k00_numpre = ($this->k00_numpre == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_numpre"]:$this->k00_numpre);
       $this->k00_numpar = ($this->k00_numpar == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_numpar"]:$this->k00_numpar);
       $this->k00_numtot = ($this->k00_numtot == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_numtot"]:$this->k00_numtot);
       $this->k00_numdig = ($this->k00_numdig == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_numdig"]:$this->k00_numdig);
       $this->k00_conta = ($this->k00_conta == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_conta"]:$this->k00_conta);
       if($this->k00_dtpaga == ""){
         $this->k00_dtpaga_dia = ($this->k00_dtpaga_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_dtpaga_dia"]:$this->k00_dtpaga_dia);
         $this->k00_dtpaga_mes = ($this->k00_dtpaga_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_dtpaga_mes"]:$this->k00_dtpaga_mes);
         $this->k00_dtpaga_ano = ($this->k00_dtpaga_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_dtpaga_ano"]:$this->k00_dtpaga_ano);
         if($this->k00_dtpaga_dia != ""){
            $this->k00_dtpaga = $this->k00_dtpaga_ano."-".$this->k00_dtpaga_mes."-".$this->k00_dtpaga_dia;
         }
       }
       $this->k00_numnov = ($this->k00_numnov == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_numnov"]:$this->k00_numnov);
     }else{
     }
   }
   // funcao para inclusao
   function incluir (){
      $this->atualizacampos();
     if($this->k00_numcgm == null ){
       $this->erro_sql = " Campo cgm nao Informado.";
       $this->erro_campo = "k00_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_dtoper == null ){
       $this->erro_sql = " Campo DT.Lanc nao Informado.";
       $this->erro_campo = "k00_dtoper_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_receit == null ){
       $this->erro_sql = " Campo Receita nao Informado.";
       $this->erro_campo = "k00_receit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_hist == null ){
       $this->erro_sql = " Campo Histórico de Cálculo nao Informado.";
       $this->erro_campo = "k00_hist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_valor == null ){
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "k00_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_dtvenc == null ){
       $this->erro_sql = " Campo DT.Venc nao Informado.";
       $this->erro_campo = "k00_dtvenc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_numpre == null ){
       $this->erro_sql = " Campo Numpre nao Informado.";
       $this->erro_campo = "k00_numpre";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_numpar == null ){
       $this->erro_sql = " Campo Parcela nao Informado.";
       $this->erro_campo = "k00_numpar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_numtot == null ){
       $this->erro_sql = " Campo Total de Parcelas nao Informado.";
       $this->erro_campo = "k00_numtot";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_numdig == null ){
       $this->erro_sql = " Campo D nao Informado.";
       $this->erro_campo = "k00_numdig";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_conta == null ){
       $this->erro_sql = " Campo Conta nao Informado.";
       $this->erro_campo = "k00_conta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_dtpaga == null ){
       $this->k00_dtpaga = "null";
     }
     if($this->k00_numnov == null ){
       $this->erro_sql = " Campo Codigo Auxiliar nao Informado.";
       $this->erro_campo = "k00_numnov";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into recibopaga(
                                       k00_numcgm
                                      ,k00_dtoper
                                      ,k00_receit
                                      ,k00_hist
                                      ,k00_valor
                                      ,k00_dtvenc
                                      ,k00_numpre
                                      ,k00_numpar
                                      ,k00_numtot
                                      ,k00_numdig
                                      ,k00_conta
                                      ,k00_dtpaga
                                      ,k00_numnov
                       )
                values (
                                $this->k00_numcgm
                               ,".($this->k00_dtoper == "null" || $this->k00_dtoper == ""?"null":"'".$this->k00_dtoper."'")."
                               ,$this->k00_receit
                               ,$this->k00_hist
                               ,$this->k00_valor
                               ,".($this->k00_dtvenc == "null" || $this->k00_dtvenc == ""?"null":"'".$this->k00_dtvenc."'")."
                               ,$this->k00_numpre
                               ,$this->k00_numpar
                               ,$this->k00_numtot
                               ,$this->k00_numdig
                               ,$this->k00_conta
                               ,".($this->k00_dtpaga == "null" || $this->k00_dtpaga == ""?"null":"'".$this->k00_dtpaga."'")."
                               ,$this->k00_numnov
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Pagamento Recibo () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Pagamento Recibo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Pagamento Recibo () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     return true;
   }
   // funcao para alteracao
   function alterar ( $oid=null, $dbwhere=null) {
      $this->atualizacampos();
     $sql = " update recibopaga set ";
     $virgula = "";
     if(trim($this->k00_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_numcgm"])){
       $sql  .= $virgula." k00_numcgm = $this->k00_numcgm ";
       $virgula = ",";
       if(trim($this->k00_numcgm) == null ){
         $this->erro_sql = " Campo cgm nao Informado.";
         $this->erro_campo = "k00_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_dtoper)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_dtoper_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k00_dtoper_dia"] !="") ){
       $sql  .= $virgula." k00_dtoper = '$this->k00_dtoper' ";
       $virgula = ",";
       if(trim($this->k00_dtoper) == null ){
         $this->erro_sql = " Campo DT.Lanc nao Informado.";
         $this->erro_campo = "k00_dtoper_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["k00_dtoper_dia"])){
         $sql  .= $virgula." k00_dtoper = null ";
         $virgula = ",";
         if(trim($this->k00_dtoper) == null ){
           $this->erro_sql = " Campo DT.Lanc nao Informado.";
           $this->erro_campo = "k00_dtoper_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k00_receit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_receit"])){
       $sql  .= $virgula." k00_receit = $this->k00_receit ";
       $virgula = ",";
       if(trim($this->k00_receit) == null ){
         $this->erro_sql = " Campo Receita nao Informado.";
         $this->erro_campo = "k00_receit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_hist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_hist"])){
       $sql  .= $virgula." k00_hist = $this->k00_hist ";
       $virgula = ",";
       if(trim($this->k00_hist) == null ){
         $this->erro_sql = " Campo Histórico de Cálculo nao Informado.";
         $this->erro_campo = "k00_hist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_valor"])){
       $sql  .= $virgula." k00_valor = $this->k00_valor ";
       $virgula = ",";
       if(trim($this->k00_valor) == null ){
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "k00_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_dtvenc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_dtvenc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k00_dtvenc_dia"] !="") ){
       $sql  .= $virgula." k00_dtvenc = '$this->k00_dtvenc' ";
       $virgula = ",";
       if(trim($this->k00_dtvenc) == null ){
         $this->erro_sql = " Campo DT.Venc nao Informado.";
         $this->erro_campo = "k00_dtvenc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["k00_dtvenc_dia"])){
         $sql  .= $virgula." k00_dtvenc = null ";
         $virgula = ",";
         if(trim($this->k00_dtvenc) == null ){
           $this->erro_sql = " Campo DT.Venc nao Informado.";
           $this->erro_campo = "k00_dtvenc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k00_numpre)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_numpre"])){
       $sql  .= $virgula." k00_numpre = $this->k00_numpre ";
       $virgula = ",";
       if(trim($this->k00_numpre) == null ){
         $this->erro_sql = " Campo Numpre nao Informado.";
         $this->erro_campo = "k00_numpre";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_numpar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_numpar"])){
       $sql  .= $virgula." k00_numpar = $this->k00_numpar ";
       $virgula = ",";
       if(trim($this->k00_numpar) == null ){
         $this->erro_sql = " Campo Parcela nao Informado.";
         $this->erro_campo = "k00_numpar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_numtot)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_numtot"])){
       $sql  .= $virgula." k00_numtot = $this->k00_numtot ";
       $virgula = ",";
       if(trim($this->k00_numtot) == null ){
         $this->erro_sql = " Campo Total de Parcelas nao Informado.";
         $this->erro_campo = "k00_numtot";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_numdig)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_numdig"])){
       $sql  .= $virgula." k00_numdig = $this->k00_numdig ";
       $virgula = ",";
       if(trim($this->k00_numdig) == null ){
         $this->erro_sql = " Campo D nao Informado.";
         $this->erro_campo = "k00_numdig";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_conta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_conta"])){
       $sql  .= $virgula." k00_conta = $this->k00_conta ";
       $virgula = ",";
       if(trim($this->k00_conta) == null ){
         $this->erro_sql = " Campo Conta nao Informado.";
         $this->erro_campo = "k00_conta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if( (trim($this->k00_dtpaga) != "null" && trim($this->k00_dtpaga)!="") || isset($GLOBALS["HTTP_POST_VARS"]["k00_dtpaga_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k00_dtpaga_dia"] !="") ){
       $sql  .= $virgula." k00_dtpaga = '$this->k00_dtpaga' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["k00_dtpaga_dia"]) || $this->k00_dtpaga == "null"){
         $sql  .= $virgula." k00_dtpaga = null ";
         $virgula = ",";
       }
     }
     if(trim($this->k00_numnov)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_numnov"])){
       $sql  .= $virgula." k00_numnov = $this->k00_numnov ";
       $virgula = ",";
       if(trim($this->k00_numnov) == null ){
         $this->erro_sql = " Campo Codigo Auxiliar nao Informado.";
         $this->erro_campo = "k00_numnov";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($dbwhere==null || $dbwhere ==""){
       $sql .= "oid = '$oid'";
     }else{
       $sql .= $dbwhere;
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Pagamento Recibo nao Alterado. Alteracao Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Pagamento Recibo nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ( $oid=null ,$dbwhere=null) {
     $sql = " delete from recibopaga
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
       $sql2 = "oid = '$oid'";
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Pagamento Recibo nao Excluído. Exclusão Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Pagamento Recibo nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
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
        $this->erro_sql   = "Record Vazio na Tabela:recibopaga";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $oid = null,$campos="recibopaga.oid,*",$ordem=null,$dbwhere=""){
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
     $sql .= " from recibopaga ";
     $sql .= "      inner join histcalc  on  histcalc.k01_codigo = recibopaga.k00_hist";
     $sql .= "      inner join tabrec  on  tabrec.k02_codigo = recibopaga.k00_receit";
     $sql .= "      inner join tabrecjm  on  tabrecjm.k02_codjm = tabrec.k02_codjm";
     $sql2 = "";
     if($dbwhere==""){
       if( $oid != "" && $oid != null){
          $sql2 = " where recibopaga.oid = '$oid'";
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
   function sql_query_file ( $oid = null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from recibopaga ";
     $sql2 = "";
     if($dbwhere==""){
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

   function sql_query_dadosRecibo($iNumpreRecibo) {

     $sSql = "select distinct
                     recibopagaboleto.k138_numnov as numpre_recibo,
                     recibopagaboleto.k138_data   as data_emissao,
                     recibopaga.k00_dtpaga        as data_vencimento,
                     arrebanco.k00_numbco         as nosso_numero ,
                     arrepaga.k00_dtpaga          as data_pagamento,
                     (select k00_numpre from recibopaga rp where rp.k00_numnov = {$iNumpreRecibo} limit 1) as numpre_debito
                from recibopagaboleto
                     inner join arrebanco    on arrebanco.k00_numpre  = recibopagaboleto.k138_numnov
                     inner join recibopaga   on recibopaga.k00_numnov = recibopagaboleto.k138_numnov
                     left  join arrepaga     on arrepaga.k00_numpre   = recibopaga.k00_numpre
                                            and arrepaga.k00_numpar   = recibopaga.k00_numpar
               where k138_numnov = {$iNumpreRecibo}                                                  ";

     return $sSql;
   }


  function sql_query_descontoConced_cotaUnica($iRegra = 0, $iAno = null, $dDataInicial = null, $dDataFinal = null) {

    $sSql  = " select matricula,                                                                                         ";
    $sSql .= "        (select rvNome                                                                                     ";
    $sSql .= "           from fc_busca_envolvidos(true, {$iRegra}, 'M', matricula)                                       ";
    $sSql .= "          limit 1) as contribuinte,                                                                        ";
    $sSql .= "        receita,                                                                                           ";
    $sSql .= "        descricao,                                                                                         ";
    $sSql .= "        sum(vlrcalculado) as vlrcalculado,                                                                 ";
    $sSql .= "        vlrdescontoabatimento,                                                                             ";
    $sSql .= "        sum((vlrcalculado - vlrdescontoabatimento)) as vlrliquido,                                         ";
    $sSql .= "       case when minhist <> 918                                                                     \n";
    $sSql .= "              then vlrcalculado - abs(sum(abs(vlrdesconto)))                                        \n";
    $sSql .= "              else abs(sum(abs(vlrdesconto)))                                                       \n";
    $sSql .= "       end as vlrdesconto,                                                                          \n";
    $sSql .= "        vlrpago,                                                                                           ";
    $sSql .= "        case                                                                                               ";
    $sSql .= "         when vlrdesconto = 0 then 0                                                                       ";
    $sSql .= "         else qtd_exists                                                                                   ";
    $sSql .= "        end as qtd                                                                                         ";
    $sSql .= "   from (select iptunump.j20_matric as matricula,                                                          ";
    $sSql .= "                iptunump.j20_numpre as numpre,                                                             ";
    $sSql .= "                arrepaga.k00_receit as receita,                                                            ";
    $sSql .= "                tabrec.k02_descr as descricao,                                                             ";
    $sSql .= "                iptucalv.j21_valor as vlrcalculado,                                                        ";
    $sSql .= "                sum(arrepaga.k00_valor) as vlrpago,                                                        ";
    $sSql .= "                coalesce(sum(abatimentoarreckey.k128_valorabatido +                                        ";
    $sSql .= "                    abatimentoarreckey.k128_correcao +                                                     ";
    $sSql .= "                    abatimentoarreckey.k128_juros +                                                        ";
    $sSql .= "                    abatimentoarreckey.k128_multa), 0) as vlrdescontoabatimento,                           ";
    $sSql .= "                sum(case                                                                                   ";
    $sSql .= "                     when arrepaga.k00_hist not in (990, 918) then 0                                       ";
    $sSql .= "                     else abs(arrepaga.k00_valor)                                                          ";
    $sSql .= "                    end) as vlrdesconto,                                                                   ";
    $sSql .= "                (select *                                                                                  ";
    $sSql .= "                   from fc_consultadescontounica(iptunump.j20_numpre)) as qtd_exists,                      ";
    $sSql .= "                (select min(k00_hist)                                               \n";
    $sSql .= "                    from arrepaga as x                                              \n";
    $sSql .= "                   where x.k00_numpre = arrepaga.k00_numpre                         \n";
    $sSql .= "                     and x.k00_hist in (990, 918)                                   \n";
    $sSql .= "                ) as minhist                                                        \n";
    $sSql .= "           from iptunump                                                                                   ";
    $sSql .= "                inner join iptucalv on iptucalv.j21_matric = iptunump.j20_matric                           ";
    $sSql .= "                                   and iptucalv.j21_anousu = iptunump.j20_anousu                           ";
    $sSql .= "                inner join tabrec on tabrec.k02_codigo = iptucalv.j21_receit                               ";
    $sSql .= "                inner join arrepaga on arrepaga.k00_numpre = iptunump.j20_numpre                           ";
    $sSql .= "                                   and arrepaga.k00_receit = iptucalv.j21_receit                           ";
    $sSql .= "                left join arrecad on arrecad.k00_numpre = iptunump.j20_numpre                              ";
    $sSql .= "                left join arreckey on arreckey.k00_numpre = arrepaga.k00_numpre                            ";
    $sSql .= "                                  and arreckey.k00_numpar = arrepaga.k00_numpar                            ";
    $sSql .= "                                  and arreckey.k00_receit = arrepaga.k00_receit                            ";
    $sSql .= "                                  and arreckey.k00_hist = arrepaga.k00_hist                                ";
    $sSql .= "                left join abatimentoarreckey on abatimentoarreckey.k128_arreckey = arreckey.k00_sequencial ";
    $sSql .= "                left join abatimento on abatimento.k125_sequencial = abatimentoarreckey.k128_abatimento    ";
    $sSql .= "                                    and abatimento.k125_tipoabatimento = 2                                 ";
    $sSql .= "          where arrecad.k00_numpre is null                                                                 ";
    $sSql .= "            and exists (select 1                                                                           ";
    $sSql .= "                          from recibounica                                                                 ";
    $sSql .= "                         where recibounica.k00_numpre = iptunump.j20_numpre)                               ";
    $sSql .= "            and iptunump.j20_anousu = {$iAno}                                                              ";
    $sSql .= "            and arrepaga.k00_dtpaga between '{$dDataInicial}' and '{$dDataFinal}'                          ";
    $sSql .= "          group by matricula,                                                                              ";
    $sSql .= "                   numpre,                                                                                 ";
    $sSql .= "                   vlrcalculado,                                                                           ";
    $sSql .= "                   receita,                                                                                ";
    $sSql .= "                   descricao,                                                                              ";
    $sSql .= "                   minhist                                                                                 ";
    $sSql .= "        ) as view                                                                                          ";
    $sSql .= "  where qtd_exists > 0                                                                                     ";
    $sSql .= "  group by matricula,                                                                                      ";
    $sSql .= "           contribuinte,                                                                                   ";
    $sSql .= "           receita,                                                                                        ";
    $sSql .= "           descricao,                                                                                      ";
    $sSql .= "           vlrdescontoabatimento,                                                                          ";
    $sSql .= "           vlrdesconto,                                                                                    ";
    $sSql .= "           vlrpago,                                                                                        ";
    $sSql .= "           vlrcalculado,                                                                                            ";
    $sSql .= "           qtd,                                                                                            ";
    $sSql .= "           minhist                                                                                         ";
    $sSql .= "  order by matricula,                                                                                      ";
    $sSql .= "           receita                                                                                         ";

    return $sSql;
  }

  /**
   * Query para buscar valores por receita
   * @param  integer $iInstituicao Sequencial Instituicao
   * @param  integer $iAnousu      Ano do exercicio
   * @param  integer $iNumnov      Numpre do Recibo
   * @return string                Query
   */
  public function sql_query_valores_receita($iInstituicao, $iAnousu, $iNumnov) {

    $sSqlValoresPorReceita  = "   select r.k00_numcgm,                                                    ";
    $sSqlValoresPorReceita .= "          r.k00_receit,                                                    ";
    $sSqlValoresPorReceita .= "          upper(t.k02_descr)  as k02_descr,                                ";
    $sSqlValoresPorReceita .= "          upper(t.k02_drecei) as k02_drecei,                               ";
    $sSqlValoresPorReceita .= "          coalesce(upper(k07_descr),' ') as k07_descr,                     ";
    $sSqlValoresPorReceita .= "          sum(r.k00_valor) as valor,                                       ";

    $sSqlValoresPorReceita .= "          case                                                             ";
    $sSqlValoresPorReceita .= "            when taborc.k02_codigo is null                                 ";
    $sSqlValoresPorReceita .= "              then tabplan.k02_reduz                                       ";
    $sSqlValoresPorReceita .= "            else                                                           ";
    $sSqlValoresPorReceita .= "              taborc.k02_codrec                                            ";
    $sSqlValoresPorReceita .= "          end as codreduz,                                                 ";

    $sSqlValoresPorReceita .= "          k00_hist,                                                        ";
    $sSqlValoresPorReceita .= "          (select (select k02_codigo                                       ";
    $sSqlValoresPorReceita .= "                     from tabrec                                           ";
    $sSqlValoresPorReceita .= "                    where k02_recjur = k00_receit                          ";
    $sSqlValoresPorReceita .= "                       or k02_recmul = k00_receit limit 1                  ";
    $sSqlValoresPorReceita .= "                   ) is not null                                           ";
    $sSqlValoresPorReceita .= "          ) as codtipo                                                     ";

    $sSqlValoresPorReceita .= "     from recibopaga r                                                     ";
    $sSqlValoresPorReceita .= "          inner join tabrec t on t.k02_codigo       = r.k00_receit         ";
    $sSqlValoresPorReceita .= "          inner join tabrecjm on tabrecjm.k02_codjm = t.k02_codjm          ";
    $sSqlValoresPorReceita .= "          left join tabdesc   on k07_codigo         = t.k02_codigo         ";
    $sSqlValoresPorReceita .= "                             and k07_instit         = {$iInstituicao}      ";
    $sSqlValoresPorReceita .= "          left join taborc    on t.k02_codigo       = taborc.k02_codigo    ";
    $sSqlValoresPorReceita .= "                             and taborc.k02_anousu  = {$iAnousu}           ";
    $sSqlValoresPorReceita .= "          left join tabplan   on t.k02_codigo       = tabplan.k02_codigo   ";
    $sSqlValoresPorReceita .= "                             and tabplan.k02_anousu = {$iAnousu}           ";
    $sSqlValoresPorReceita .= "    where r.k00_numnov = {$iNumnov}                                        ";
    $sSqlValoresPorReceita .= " group by r.k00_receit,                                                    ";
    $sSqlValoresPorReceita .= "          t.k02_descr,                                                     ";
    $sSqlValoresPorReceita .= "          t.k02_drecei,                                                    ";
    $sSqlValoresPorReceita .= "          r.k00_numcgm,                                                    ";
    $sSqlValoresPorReceita .= "          k07_descr,                                                       ";
    $sSqlValoresPorReceita .= "          codreduz,                                                        ";
    $sSqlValoresPorReceita .= "          r.k00_hist                                                       ";

    return $sSqlValoresPorReceita;

  }

  /**
   * [sql_query_dados_pagamento description]
   * @param  integer $iAnoExercicio [description]
   * @param  string  $sWhere1       [description]
   * @param  string  $sWhere2       [description]
   * @param  integer $iNumnov       [description]
   * @param  string  $sCampoNull    [description]
   * @param  string  $sCampoJoin    [description]
   * @param  string  $sJoinHist     [description]
   * @param  string  $sGroupBy      [description]
   * @return [type]                [description]
   */
  public function sql_query_dados_pagamento($iAnoExercicio, $sWhere1, $sWhere2, $iNumnov, $sCampoNull = "", $sCampoJoin = "", $sJoinHist = "", $sGroupBy = "") {

    $sSql  = " select * from (                                                                                                                              ";
    $sSql .= "                select                                                                                                                        ";
    $sSql .= "                  r.k00_numcgm,                                                                                                               ";
    $sSql .= "                  r.k00_receit,                                                                                                               ";
    $sSql .= "                  null as k00_hist,                                                                                                           ";
    $sSql .= "                  case when taborc.k02_codigo is null                                                                                         ";
    $sSql .= "                    then tabplan.k02_reduz                                                                                                    ";
    $sSql .= "                    else taborc.k02_codrec                                                                                                    ";
    $sSql .= "                  end as codreduz,                                                                                                            ";
    $sSql .= "                  t.k02_descr,                                                                                                                ";
    $sSql .= "                  t.k02_drecei,                                                                                                               ";
    $sSql .= "                  r.k00_dtpaga as k00_dtpaga,                                                                                                 ";
    $sSql .= "                  sum(r.k00_valor) as valor,                                                                                                  ";
    $sSql .= "                  (select                                                                                                                     ";
    $sSql .= "                      (select k02_codigo from tabrec                                                                                          ";
    $sSql .= "                        where                                                                                                                 ";
    $sSql .= "                          k02_recjur = r.k00_receit or                                                                                        ";
    $sSql .= "                          k02_recmul = r.k00_receit                                                                                           ";
    $sSql .= "                        limit 1)                                                                                                              ";
    $sSql .= "                  is not null) as codtipo                                                                                                     ";
    $sSql .= "                  {$sCampoNull}                                                                                                               ";
    $sSql .= "                from recibopaga r                                                                                                             ";
    $sSql .= "                     inner join tabrec t on t.k02_codigo = r.k00_receit                                                                       ";
    $sSql .= "                     inner join tabrecjm on tabrecjm.k02_codjm = t.k02_codjm                                                                  ";
    $sSql .= "                     left outer join taborc  on t.k02_codigo = taborc.k02_codigo and taborc.k02_anousu = {$iAnoExercicio}                     ";
    $sSql .= "                     left outer join tabplan  on t.k02_codigo = tabplan.k02_codigo and tabplan.k02_anousu = {$iAnoExercicio}                  ";
    $sSql .= "                where r.k00_numnov = {$iNumnov}                                                                                               ";
    $sSql .= "                 and {$sWhere1}                                                                                                               ";
    $sSql .= "                group by r.k00_dtpaga,                                                                                                        ";
    $sSql .= "                         r.k00_receit,                                                                                                        ";
    $sSql .= "                         t.k02_descr,                                                                                                         ";
    $sSql .= "                         t.k02_drecei,                                                                                                        ";
    $sSql .= "                         r.k00_numcgm,                                                                                                        ";
    $sSql .= "                         codreduz                                                                                                             ";
    $sSql .= "                 union                                                                                                                        ";
    $sSql .= "                   select r.k00_numcgm,                                                                                                       ";
    $sSql .= "                          r.k00_receit,                                                                                                       ";
    $sSql .= "                          r.k00_hist,                                                                                                         ";
    $sSql .= "                          case when taborc.k02_codigo is null                                                                                 ";
    $sSql .= "                               then tabplan.k02_reduz                                                                                         ";
    $sSql .= "                               else taborc.k02_codrec                                                                                         ";
    $sSql .= "                           end as codreduz,                                                                                                   ";
    $sSql .= "                          t.k02_descr,                                                                                                        ";
    $sSql .= "                          t.k02_drecei,                                                                                                       ";
    $sSql .= "                          r.k00_dtpaga as k00_dtpaga,                                                                                         ";
    $sSql .= "                          sum(r.k00_valor) as valor,                                                                                          ";
    $sSql .= "                          (select (select k02_codigo                                                                                          ";
    $sSql .= "                                    from tabrec                                                                                               ";
    $sSql .= "                                   where k02_recjur = r.k00_receit                                                                            ";
    $sSql .= "                                      or k02_recmul = r.k00_receit limit 1)                                                                   ";
    $sSql .= "                                 is not null ) as codtipo                                                                                     ";
    $sSql .= "                                 {$sCampoJoin}                                                                                                ";
    $sSql .= "                     from recibopaga r                                                                                                        ";
    $sSql .= "                          inner join tabrec t on t.k02_codigo = r.k00_receit                                                                  ";
    $sSql .= "                          inner join tabrecjm on tabrecjm.k02_codjm = t.k02_codjm                                                             ";
    $sSql .= "                          left outer join taborc   on t.k02_codigo = taborc.k02_codigo  and taborc.k02_anousu = {$iAnoExercicio}              ";
    $sSql .= "                          left outer join tabplan  on t.k02_codigo = tabplan.k02_codigo and tabplan.k02_anousu = {$iAnoExercicio}             ";
    $sSql .= "                          {$sJoinHist}                                                                                                        ";
    $sSql .= "                    where r.k00_numnov = {$iNumnov}                                                                                           ";
    $sSql .= "                      and {$sWhere2}                                                                                                          ";
    $sSql .= "                    group by r.k00_dtpaga,                                                                                                    ";
    $sSql .= "                             r.k00_receit,                                                                                                    ";
    $sSql .= "                             r.k00_hist,                                                                                                      ";
    $sSql .= "                             t.k02_descr,                                                                                                     ";
    $sSql .= "                             t.k02_drecei,                                                                                                    ";
    $sSql .= "                             r.k00_numcgm,                                                                                                    ";
    $sSql .= "                             codreduz                                                                                                         ";
    $sSql .= "                             {$sGroupBy}) as x order by k00_receit, valor desc                                                                ";

    return $sSql;
  }

  /**
   * Montamos a query para alterar a data de vencimento do recibo pelo numnov
   *
   * @param  integer $iNumnov
   * @param  string  $sDataVencimento
   * @return string
   */
  public function sql_query_altera_data_vencimento( $iNumnov, $sDataVencimento ) {

    $sSqlAtualizaVencimento  = " update recibopaga                        ";
    $sSqlAtualizaVencimento .= "    set k00_dtpaga = '{$sDataVencimento}' ";
    $sSqlAtualizaVencimento .= "  where k00_numnov = {$iNumnov}           ";

    return $sSqlAtualizaVencimento;
  }

  /**
   * Busca dados do cgm do recibo
   *
   * @param  string  $sCampos
   * @param  integer $iNumpre
   * @return string  retorna a query montada
   */
  public function sql_query_cgm($sCampos, $iNumpre) {

   $sSqlCGM  =  "select {$sCampos} ";
   $sSqlCGM .=  "  from cgm ";
   $sSqlCGM .=  "       inner join recibo     on z01_numcgm = recibo.k00_numcgm";
   $sSqlCGM .=  " where recibo.k00_numpre = {$iNumpre}";

   $sSqlCGM .=  " union ";

   $sSqlCGM .=  "select {$sCampos}";
   $sSqlCGM .=  "  from cgm";
   $sSqlCGM .=  "       inner join arrenumcgm on k00_numcgm = z01_numcgm";
   $sSqlCGM .=  "       inner join recibopaga on arrenumcgm.k00_numpre = recibopaga.k00_numpre";
   $sSqlCGM .=  " where recibopaga.k00_numpre = {$iNumpre}";

   
   return $sSqlCGM;
  }


  /**
   * Busca dados do cgm do recibo
   *
   * @param  string  $sCampos
   * @param  integer $iNumpre
   * @return string  retorna a query montada
   */
  public function  sql_query_cgm_webservice_caixa($sRegra, $iRegraCgmIptu, $sTipoOrigem, $iCodOrigem) 
  {
     $sQuery = "SELECT * FROM fc_busca_envolvidos($sRegra, $iRegraCgmIptu, '$sTipoOrigem', $iCodOrigem) AS principal "; 

     if ($sTipoOrigem == 'M') {
        
        $sQuery .= "LEFT JOIN promitente ON promitente.j41_numcgm = principal.rinumcgm  AND  promitente.j41_matric = $iCodOrigem "; 
        
        if (!$iRegraCgmIptu){
           
           $sQuery .= "AND j41_tipopro = 't'";    
        }
     }
     
     return $sQuery;
  }

  /**
   * Busca dados do cgm do cgm passado
   *
   * @param  integer $iNumpre
   * @return string  retorna a query montada
   */
   
  public function  sql_query_info_cgm($iNumero)
  { 

   return "select z01_numcgm, z01_nome, z01_cgccpf
                          from cgm
                              where z01_numcgm = $iNumero " ;
   }

}
?>
