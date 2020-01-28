<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
//CLASSE DA ENTIDADE arrepaga
class cl_arrepaga {
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
                 ";
   //funcao construtor da classe
   function cl_arrepaga() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("arrepaga");
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
       $this->erro_sql = " Campo Tot nao Informado.";
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
       $this->erro_sql = " Campo Data do pagamento nao Informado.";
       $this->erro_campo = "k00_dtpaga_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into arrepaga(
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
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " () nao Incluído. Inclusao Abortada.";
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
   function alterar ( $oid=null , $sWhere=null) {
      $this->atualizacampos();
     $sql = " update arrepaga set ";
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
         $this->erro_sql = " Campo Tot nao Informado.";
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
     if(trim($this->k00_dtpaga)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_dtpaga_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k00_dtpaga_dia"] !="") ){
       $sql  .= $virgula." k00_dtpaga = '$this->k00_dtpaga' ";
       $virgula = ",";
       if(trim($this->k00_dtpaga) == null ){
         $this->erro_sql = " Campo Data do pagamento nao Informado.";
         $this->erro_campo = "k00_dtpaga_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["k00_dtpaga_dia"])){
         $sql  .= $virgula." k00_dtpaga = null ";
         $virgula = ",";
         if(trim($this->k00_dtpaga) == null ){
           $this->erro_sql = " Campo Data do pagamento nao Informado.";
           $this->erro_campo = "k00_dtpaga_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";

     if (!empty($oid)) {
       $sql .= "oid = '$oid'";
     } else if (!empty($sWhere)) {
       $sql .= $sWhere;
     }

     $result = db_query($sql);

     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
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
     $sql = " delete from arrepaga
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
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
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
        $this->erro_sql   = "Record Vazio na Tabela:arrepaga";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $oid = null,$campos="arrepaga.oid,*",$ordem=null,$dbwhere=""){
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
     $sql .= " from arrepaga ";
     $sql2 = "";
     if($dbwhere==""){
       if( $oid != "" && $oid != null){
          $sql2 = " where arrepaga.oid = $oid";
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
     $sql .= " from arrepaga ";
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
  
  function sql_queryDescontoConcedidoPorRegra ($dDataInicial, $dDataFinal) {
    
    $iInstituicao         = db_getsession('DB_instit');
    $iAnoUsu              = db_getsession('DB_anousu');
    
    list($iAnoInicio, $iMesInicio, $iDiaInicio) = explode("-", $dDataInicial);
    list($iAnoFim, $iMesFim, $iDiaFim)          = explode("-", $dDataFinal);
    
    for ( $iIndice = $iAnoInicio; $iIndice <= $iAnoFim; $iIndice++ ) {
      $aListaExercicios[] = $iIndice;
    }

    $aListaExercicios = array_unique($aListaExercicios);
    $sListaExercicios = implode(",", $aListaExercicios);
    

    $sSqlDividasCertidao  = "  create temp table w_origem_dividas as                                                \n";
    $sSqlDividasCertidao .= "  select 1 as tipo,                                                                    \n";
    $sSqlDividasCertidao .= "         'CERTIDÃO DE PARCELAMENTO DE DIVIDA' as tipo_certidao,                        \n";
    $sSqlDividasCertidao .= "         certter.v14_certid as certidao,                                               \n";
    $sSqlDividasCertidao .= "         certter.v14_parcel as parcel,                                                 \n";
    $sSqlDividasCertidao .= "         certter.v14_parcel as parcelori,                                              \n";
    $sSqlDividasCertidao .= "         0 as inicial,                                                                 \n";
    $sSqlDividasCertidao .= "         divida.*                                                                      \n";
    $sSqlDividasCertidao .= "    from certter                                                                       \n";
    $sSqlDividasCertidao .= "         inner join termo     on termo.v07_parcel  = certter.v14_parcel                \n";
    $sSqlDividasCertidao .= "         inner join termodiv  on termodiv.parcel   = certter.v14_parcel                \n";
    $sSqlDividasCertidao .= "         inner join divida    on divida.v01_coddiv = termodiv.coddiv                   \n";
    $sSqlDividasCertidao .= "                             and divida.v01_instit = {$iInstituicao}                   \n";
    $sSqlDividasCertidao .= "                             and divida.v01_exerc in ( {$sListaExercicios} )           \n";
    $sSqlDividasCertidao .= "         left join inicialcert on inicialcert.v51_certidao = certter.v14_certid        \n";
    $sSqlDividasCertidao .= "   where inicialcert.v51_certidao is null                                              \n";
    $sSqlDividasCertidao .= "                                                                                       \n";
    $sSqlDividasCertidao .= "  union all                                                                            \n";
    $sSqlDividasCertidao .= "                                                                                       \n";
    $sSqlDividasCertidao .= "  select 2 as tipo ,                                                                   \n";
    $sSqlDividasCertidao .= "         'PARCELAMENTO DE INICIAL DE CERTIDAO DO PARCELAMENTO' as tipo_certidao,       \n";
    $sSqlDividasCertidao .= "         certter.v14_certid as certidao,                                               \n";
    $sSqlDividasCertidao .= "         termo.v07_parcel as parcel,                                                   \n";
    $sSqlDividasCertidao .= "         certter.v14_parcel as parcelori,                                              \n";
    $sSqlDividasCertidao .= "         inicialcert.v51_inicial as inicial,                                           \n";
    $sSqlDividasCertidao .= "         divida.*                                                                      \n";
    $sSqlDividasCertidao .= "    from termoini                                                                      \n";
    $sSqlDividasCertidao .= "         inner join termo       on termo.v07_parcel        = termoini.parcel           \n";
    $sSqlDividasCertidao .= "         inner join inicialcert on inicialcert.v51_inicial = termoini.inicial          \n";
    $sSqlDividasCertidao .= "         inner join certter     on certter.v14_certid      = inicialcert.v51_certidao  \n";
    $sSqlDividasCertidao .= "         inner join termodiv    on termodiv.parcel         = certter.v14_parcel        \n";
    $sSqlDividasCertidao .= "         inner join divida      on divida.v01_coddiv       = termodiv.coddiv           \n";
    $sSqlDividasCertidao .= "                               and divida.v01_instit       = {$iInstituicao}           \n";
    $sSqlDividasCertidao .= "                               and divida.v01_exerc in ( {$sListaExercicios} )         \n";
    $sSqlDividasCertidao .= "                                                                                       \n";
    $sSqlDividasCertidao .= "  union all                                                                            \n";
    $sSqlDividasCertidao .= "                                                                                       \n";
    $sSqlDividasCertidao .= "  select 3 as tipo,                                                                    \n";
    $sSqlDividasCertidao .= "         'PARCELAMENTO DE INICIAL DE CERTIDAO DE DIVIDA' as tipo_certidao,             \n";
    $sSqlDividasCertidao .= "         certdiv.v14_certid as certidao,                                               \n";
    $sSqlDividasCertidao .= "         termoini.parcel    as parcel,                                                 \n";
    $sSqlDividasCertidao .= "         termoini.parcel    as parcelori,                                              \n";
    $sSqlDividasCertidao .= "         inicialcert.v51_inicial as inicial,                                           \n";
    $sSqlDividasCertidao .= "         divida.*                                                                      \n";
    $sSqlDividasCertidao .= "    from termoini                                                                      \n";
    $sSqlDividasCertidao .= "         inner join termo       on termo.v07_parcel        = termoini.parcel           \n";
    $sSqlDividasCertidao .= "         inner join inicialcert on inicialcert.v51_inicial = termoini.inicial          \n";
    $sSqlDividasCertidao .= "         inner join certdiv     on certdiv.v14_certid      = inicialcert.v51_certidao  \n";
    $sSqlDividasCertidao .= "         inner join divida      on divida.v01_coddiv       = certdiv.v14_coddiv        \n";
    $sSqlDividasCertidao .= "                               and divida.v01_instit       = {$iInstituicao}           \n";
    $sSqlDividasCertidao .= "                               and divida.v01_exerc in ( {$sListaExercicios} );        \n";
    $sSqlDividasCertidao .= "                                                                                       \n";
    $sSqlDividasCertidao .= "  create index w_origem_dividas_1_in on w_origem_dividas(inicial);                     \n";
    $sSqlDividasCertidao .= "  create index w_origem_dividas_2_in on w_origem_dividas(v01_numpre, v01_numpar);      \n";
    
    $rsDividasCertidao = db_query($sSqlDividasCertidao);
    
    if (!$rsDividasCertidao) {
      throw new Exception('Erro ao consultar dívidas para o processamento.');
    }

    $sSqlDescontos  = " select k00_numpre                               as numpre,                                                                                                                \n";
    $sSqlDescontos .= "        z01_nome                                 as contribuinte,                                                                                                          \n";
    $sSqlDescontos .= "        fc_origem_numpre(k00_numpre,1)           as origem,                                                                                                                \n";
    $sSqlDescontos .= "        k00_receit                               as receit,                                                                                                                \n";
    $sSqlDescontos .= "        k03_tipo                                 as tipo,                                                                                                                  \n";
    $sSqlDescontos .= "        k00_descr                                as descrtipo,                                                                                                             \n";
    $sSqlDescontos .= "        coalesce(v03_codigo,0)                   as proced,                                                                                                                \n";
    $sSqlDescontos .= "        case                                                                                                                                                               \n";
    $sSqlDescontos .= "           when v03_codigo is not null then v03_descr                                                                                                                      \n";
    $sSqlDescontos .= "           else 'SEM PROCEDÊNCIA'                                                                                                                                          \n";
    $sSqlDescontos .= "        end                                      as descrproced,                                                                                                           \n";
    $sSqlDescontos .= "        coalesce(v07_sequencial,0)               as tipoproced,                                                                                                            \n";
    $sSqlDescontos .= "        case                                                                                                                                                               \n";
    $sSqlDescontos .= "           when v07_sequencial is not null then v07_descricao                                                                                                              \n";
    $sSqlDescontos .= "           else 'SEM TIPO DE PROCEDÊNCIA'                                                                                                                                  \n";
    $sSqlDescontos .= "        end                                      as descrtipoproced,                                                                                                       \n";
    $sSqlDescontos .= "        k02_codrec                               as receitorc,                                                                                                             \n";
    $sSqlDescontos .= "        k02_estorc                               as receitorcamentoestrutural,                                                                                             \n";
    $sSqlDescontos .= "        k02_drecei                               as descrreceit,                                                                                                           \n";
    $sSqlDescontos .= "        round(sum(valor_sem_desconto),2)         as valor_pagar,                                                                                                           \n";
    $sSqlDescontos .= "        round(sum(valor_pago) ,2)                as valor_pago,                                                                                                            \n";
    $sSqlDescontos .= "        round(sum(valor_desconto),2)             as desconto,                                                                                                              \n";
    $sSqlDescontos .= "        round(sum(vlrhist),2)                    as vlrhist,                                                                                                               \n";
    $sSqlDescontos .= "        round(sum(corrigido),2)                  as vlrcorr,                                                                                                               \n";
    $sSqlDescontos .= "        round(sum(juros),2)                      as juros,                                                                                                                 \n";
    $sSqlDescontos .= "        round(sum(multa),2)                      as multa,                                                                                                                 \n";
    $sSqlDescontos .= "        round(sum(corrigido + juros + multa), 2) as total                                                                                                                  \n";
    $sSqlDescontos .= "  from (                                                                                                                                                                   \n";
    $sSqlDescontos .= "         select arrepaga.k00_numpre,                                                                                                                                       \n";
    $sSqlDescontos .= "                cgm.z01_nome,                                                                                                                                              \n";
    $sSqlDescontos .= "                arretipo.k03_tipo,                                                                                                                                         \n";
    $sSqlDescontos .= "                arretipo.k00_descr,                                                                                                                                        \n";
    $sSqlDescontos .= "                proced.v03_codigo,                                                                                                                                         \n";
    $sSqlDescontos .= "                proced.v03_descr,                                                                                                                                          \n";
    $sSqlDescontos .= "                tipoproced.v07_sequencial,                                                                                                                                 \n";
    $sSqlDescontos .= "                tipoproced.v07_descricao,                                                                                                                                  \n";
    $sSqlDescontos .= "                arrepaga.k00_receit,                                                                                                                                       \n";
    $sSqlDescontos .= "                taborc.k02_codrec,                                                                                                                                         \n";
    $sSqlDescontos .= "                taborc.k02_estorc,                                                                                                                                         \n";
    $sSqlDescontos .= "                tabrec.k02_drecei,                                                                                                                                         \n";
    $sSqlDescontos .= "                sum(fc_iif( (arrepaga.k00_valor < 0), arrepaga.k00_valor, 0::float8)) as valor_desconto,                                                                   \n";
    $sSqlDescontos .= "                sum(fc_iif( (arrepaga.k00_valor > 0), arrepaga.k00_valor, 0::float8)) as valor_sem_desconto,                                                               \n";
    $sSqlDescontos .= "                sum(arrepaga.k00_valor)                                               as valor_pago,                                                                       \n";
    $sSqlDescontos .= "                sum(arrecant.k00_valor)                                               as vlrhist,                                                                          \n";
    $sSqlDescontos .= "                sum(fc_corre( arrecant.k00_receit,                                                                                                                         \n";
    $sSqlDescontos .= "                              arrecant.k00_dtvenc,                                                                                                                         \n";
    $sSqlDescontos .= "                              arrecant.k00_valor,                                                                                                                          \n";
    $sSqlDescontos .= "                              arrepaga.k00_dtpaga,                                                                                                                         \n";
    $sSqlDescontos .= "                             cast(extract( year from arrepaga.k00_dtpaga ) as integer),                                                                                    \n";
    $sSqlDescontos .= "                             arrepaga.k00_dtpaga )) as corrigido,                                                                                                          \n";
    $sSqlDescontos .= "                sum( arrecant.k00_valor * coalesce( fc_juros( arrecant.k00_receit,                                                                                         \n";
    $sSqlDescontos .= "                                                              arrecant.k00_dtvenc,                                                                                         \n";
    $sSqlDescontos .= "                                                              arrepaga.k00_dtpaga,                                                                                         \n";
    $sSqlDescontos .= "                                                              arrepaga.k00_dtpaga,                                                                                         \n";
    $sSqlDescontos .= "                                                              false,                                                                                                       \n";
    $sSqlDescontos .= "                                                              cast(extract( year from arrepaga.k00_dtpaga ) as integer)                                                    \n";
    $sSqlDescontos .= "                                                            ),0)) as juros,                                                                                                \n";
    $sSqlDescontos .= "                sum( arrecant.k00_valor * coalesce( fc_multa( arrecant.k00_receit,                                                                                         \n";
    $sSqlDescontos .= "                                                              arrecant.k00_dtvenc,                                                                                         \n";
    $sSqlDescontos .= "                                                              arrepaga.k00_dtpaga,                                                                                         \n";
    $sSqlDescontos .= "                                                              arrecant.k00_dtoper,                                                                                         \n";
    $sSqlDescontos .= "                                                              cast(extract( year from arrepaga.k00_dtpaga ) as integer)                                                    \n";
    $sSqlDescontos .= "                                                            ),0)) as multa                                                                                                 \n";
    $sSqlDescontos .= "           from cornump                                                                                                                                                    \n";
    $sSqlDescontos .= "                inner join tabrec     on tabrec.k02_codigo         = cornump.k12_receit                                                                                    \n";
    $sSqlDescontos .= "                inner join taborc     on taborc.k02_codigo         = cornump.k12_receit                                                                                    \n";
    $sSqlDescontos .= "                                     and taborc.k02_anousu         = {$iAnoUsu}                                                                                            \n";
    $sSqlDescontos .= "                inner join arrepaga   on arrepaga.k00_numpre       = cornump.k12_numpre                                                                                    \n";
    $sSqlDescontos .= "                                     and arrepaga.k00_numpar       = cornump.k12_numpar                                                                                    \n";
    $sSqlDescontos .= "                                     and arrepaga.k00_receit       = cornump.k12_receit                                                                                    \n";
    $sSqlDescontos .= "                left  join arrecant   on arrecant.k00_numpre       = cornump.k12_numpre                                                                                    \n";
    $sSqlDescontos .= "                                     and arrecant.k00_numpar       = cornump.k12_numpar                                                                                    \n";
    $sSqlDescontos .= "                left  join arretipo   on arretipo.k00_tipo         = arrecant.k00_tipo                                                                                     \n";
    $sSqlDescontos .= "                left  join divida     on divida.v01_numpre         = cornump.k12_numpre                                                                                    \n";
    $sSqlDescontos .= "                                     and divida.v01_numpar         = cornump.k12_numpar                                                                                    \n";
    $sSqlDescontos .= "                left  join proced     on proced.v03_codigo         = divida.v01_proced                                                                                     \n";
    $sSqlDescontos .= "                left  join tipoproced on tipoproced.v07_sequencial = proced.v03_tributaria                                                                                 \n";
    $sSqlDescontos .= "                inner join cgm        on cgm.z01_numcgm            = arrepaga.k00_numcgm                                                                                   \n";
    $sSqlDescontos .= "          where cornump.k12_data between '{$dDataInicial}' and '{$dDataFinal}'                                                                                             \n";
    $sSqlDescontos .= "            and exists ( select 1                                                                                                                                          \n";
    $sSqlDescontos .= "                           from db_reciboweb                                                                                                                               \n";
    $sSqlDescontos .= "                          where db_reciboweb.k99_numpre_n = cornump.k12_numnov                                                                                             \n";
    $sSqlDescontos .= "                            and db_reciboweb.k99_tipo in (1, 2, 3)                                                                                                         \n";
    $sSqlDescontos .= "                            and exists ( select 1                                                                                                                          \n";
    $sSqlDescontos .= "                                           from arreinstit                                                                                                                 \n";
    $sSqlDescontos .= "                                          where arreinstit.k00_numpre = db_reciboweb.k99_numpre                                                                            \n";
    $sSqlDescontos .= "                                            and arreinstit.k00_instit = {$iInstituicao} ) )                                                                                \n";
    $sSqlDescontos .= "       group by arrepaga.k00_numpre,                                                                                                                                       \n";
    $sSqlDescontos .= "                cgm.z01_nome,                                                                                                                                              \n";
    $sSqlDescontos .= "                arretipo.k03_tipo,                                                                                                                                         \n";
    $sSqlDescontos .= "                arretipo.k00_descr,                                                                                                                                        \n";
    $sSqlDescontos .= "                proced.v03_codigo,                                                                                                                                         \n";
    $sSqlDescontos .= "                proced.v03_descr,                                                                                                                                          \n";
    $sSqlDescontos .= "                tipoproced.v07_sequencial,                                                                                                                                 \n";
    $sSqlDescontos .= "                tipoproced.v07_descricao,                                                                                                                                  \n";
    $sSqlDescontos .= "                arrepaga.k00_receit,                                                                                                                                       \n";
    $sSqlDescontos .= "                taborc.k02_codrec,                                                                                                                                         \n";
    $sSqlDescontos .= "                tabrec.k02_drecei,                                                                                                                                         \n";
    $sSqlDescontos .= "                k02_estorc                                                                                                                                                 \n";
    $sSqlDescontos .= "   union all                                                                                                                                                               \n";
    $sSqlDescontos .= "         select arrepaga.k00_numpre,                                                                                                                                       \n";
    $sSqlDescontos .= "                cgm.z01_nome,                                                                                                                                              \n";
    $sSqlDescontos .= "                ( select arretipo.k03_tipo                                                                                                                                 \n";
    $sSqlDescontos .= "                    from arretipo                                                                                                                                          \n";
    $sSqlDescontos .= "                   where k00_tipo = ( select k00_tipo                                                                                                                      \n";
    $sSqlDescontos .= "                                        from arrecant                                                                                                                      \n";
    $sSqlDescontos .= "                                       where k00_numpre = arrepaga.k00_numpre                                                                                              \n";
    $sSqlDescontos .= "                                        limit 1 ) ) as k03_tipo,                                                                                                           \n";
    $sSqlDescontos .= "                ( select arretipo.k00_descr                                                                                                                                \n";
    $sSqlDescontos .= "                    from arretipo                                                                                                                                          \n";
    $sSqlDescontos .= "                   where k00_tipo = ( select k00_tipo                                                                                                                      \n";
    $sSqlDescontos .= "                                        from arrecant                                                                                                                      \n";
    $sSqlDescontos .= "                                       where k00_numpre = arrepaga.k00_numpre                                                                                              \n";
    $sSqlDescontos .= "                                       limit 1 ) ) as k00_descr,                                                                                                           \n";
    $sSqlDescontos .= "                proced.v03_codigo,                                                                                                                                         \n";
    $sSqlDescontos .= "                proced.v03_descr,                                                                                                                                          \n";
    $sSqlDescontos .= "                tipoproced.v07_sequencial,                                                                                                                                 \n";
    $sSqlDescontos .= "                tipoproced.v07_descricao,                                                                                                                                  \n";
    $sSqlDescontos .= "                arrepaga.k00_receit,                                                                                                                                       \n";
    $sSqlDescontos .= "                taborc.k02_codrec,                                                                                                                                         \n";
    $sSqlDescontos .= "                taborc.k02_estorc,                                                                                                                                         \n";
    $sSqlDescontos .= "                tabrec.k02_drecei,                                                                                                                                         \n";
    $sSqlDescontos .= "                sum(fc_iif( (arrepaga.k00_valor < 0), arrepaga.k00_valor, 0::float8)) as valor_desconto,                                                                   \n";
    $sSqlDescontos .= "                sum(fc_iif( (arrepaga.k00_valor > 0), arrepaga.k00_valor, 0::float8)) as valor_sem_desconto,                                                               \n";
    $sSqlDescontos .= "                sum(arrepaga.k00_valor)                                               as valor_pago,                                                                       \n";
    $sSqlDescontos .= "                sum(coalesce(arrecant.k00_valor,0))                                   as vlrhist,                                                                          \n";
    $sSqlDescontos .= "                sum(fc_corre( arrecant.k00_receit,                                                                                                                         \n";
    $sSqlDescontos .= "                              arrecant.k00_dtvenc,                                                                                                                         \n";
    $sSqlDescontos .= "                              coalesce(arrecant.k00_valor,0),                                                                                                              \n";
    $sSqlDescontos .= "                              arrepaga.k00_dtpaga,                                                                                                                         \n";
    $sSqlDescontos .= "                              cast(extract( year from arrepaga.k00_dtpaga ) as integer),                                                                                   \n";
    $sSqlDescontos .= "                              arrepaga.k00_dtpaga )) as corrigido,                                                                                                         \n";
    $sSqlDescontos .= "                sum( coalesce(arrecant.k00_valor,0) * coalesce( fc_juros( arrecant.k00_receit,                                                                             \n";
    $sSqlDescontos .= "                                                              arrecant.k00_dtvenc,                                                                                         \n";
    $sSqlDescontos .= "                                                              arrepaga.k00_dtpaga,                                                                                         \n";
    $sSqlDescontos .= "                                                              arrepaga.k00_dtpaga,                                                                                         \n";
    $sSqlDescontos .= "                                                              false,                                                                                                       \n";
    $sSqlDescontos .= "                                                              cast(extract( year from arrepaga.k00_dtpaga ) as integer)                                                    \n";
    $sSqlDescontos .= "                                                            ),0)) as juros,                                                                                                \n";
    $sSqlDescontos .= "                sum( coalesce(arrecant.k00_valor,0) * coalesce( fc_multa( arrecant.k00_receit,                                                                             \n";
    $sSqlDescontos .= "                                                              arrecant.k00_dtvenc,                                                                                         \n";
    $sSqlDescontos .= "                                                              arrepaga.k00_dtpaga,                                                                                         \n";
    $sSqlDescontos .= "                                                              arrecant.k00_dtoper,                                                                                         \n";
    $sSqlDescontos .= "                                                              cast(extract( year from arrepaga.k00_dtpaga ) as integer)                                                    \n";
    $sSqlDescontos .= "                                                            ),0))  as multa                                                                                                \n";
    $sSqlDescontos .= "           from disbanco                                                                                                                                                   \n";
    $sSqlDescontos .= "                inner join arreidret  on arreidret.idret           = disbanco.idret                                                                                        \n";
    $sSqlDescontos .= "                inner join arrepaga   on arrepaga.k00_numpre       = arreidret.k00_numpre                                                                                  \n";
    $sSqlDescontos .= "                                     and arrepaga.k00_numpar       = arreidret.k00_numpar                                                                                  \n";
    $sSqlDescontos .= "                left  join arrecant   on arrecant.k00_numpre       = arrepaga.k00_numpre                                                                                   \n";
    $sSqlDescontos .= "                                     and arrecant.k00_numpar       = arrepaga.k00_numpar                                                                                   \n";
    $sSqlDescontos .= "                                     and arrecant.k00_receit       = arrepaga.k00_receit                                                                                   \n";
    $sSqlDescontos .= "                left  join divida     on divida.v01_numpre         = arreidret.k00_numpre                                                                                  \n";
    $sSqlDescontos .= "                                     and divida.v01_numpar         = arreidret.k00_numpar                                                                                  \n";
    $sSqlDescontos .= "                left  join proced     on proced.v03_codigo         = divida.v01_proced                                                                                     \n";
    $sSqlDescontos .= "                left  join tipoproced on tipoproced.v07_sequencial = proced.v03_tributaria                                                                                 \n";
    $sSqlDescontos .= "                inner join tabrec     on tabrec.k02_codigo         = arrepaga.k00_receit                                                                                   \n";
    $sSqlDescontos .= "                inner join taborc     on taborc.k02_codigo         = arrepaga.k00_receit                                                                                   \n";
    $sSqlDescontos .= "                                     and taborc.k02_anousu         = {$iAnoUsu}                                                                                            \n";
    $sSqlDescontos .= "                inner join cgm        on cgm.z01_numcgm            = arrepaga.k00_numcgm                                                                                   \n";
    $sSqlDescontos .= "          where disbanco.dtpago between '{$dDataInicial}' and '{$dDataFinal}'                                                                                              \n";
    $sSqlDescontos .= "            and disbanco.instit = {$iInstituicao}                                                                                                                          \n";
    $sSqlDescontos .= "            and exists ( select k99_numpre_n                                                                                                                               \n";
    $sSqlDescontos .= "                               from db_reciboweb                                                                                                                           \n";
    $sSqlDescontos .= "                                    inner join recibopaga on k00_numnov = k99_numpre_n                                                                                     \n";
    $sSqlDescontos .= "                              where k99_numpre_n = disbanco.k00_numpre                                                                                                     \n";
    $sSqlDescontos .= "                                and k99_tipo in (1, 2, 3) )                                                                                                                \n";
    $sSqlDescontos .= "        group by arrepaga.k00_numpre,                                                                                                                                      \n";
    $sSqlDescontos .= "                 cgm.z01_nome,                                                                                                                                             \n";
    $sSqlDescontos .= "                 k03_tipo,                                                                                                                                                 \n";
    $sSqlDescontos .= "                 k00_descr,                                                                                                                                                \n";
    $sSqlDescontos .= "                 proced.v03_codigo,                                                                                                                                        \n";
    $sSqlDescontos .= "                 proced.v03_descr,                                                                                                                                         \n";
    $sSqlDescontos .= "                 tipoproced.v07_sequencial,                                                                                                                                \n";
    $sSqlDescontos .= "                 tipoproced.v07_descricao,                                                                                                                                 \n";
    $sSqlDescontos .= "                 arrepaga.k00_receit,                                                                                                                                      \n";
    $sSqlDescontos .= "                 taborc.k02_codrec,                                                                                                                                        \n";
    $sSqlDescontos .= "                 tabrec.k02_drecei,                                                                                                                                        \n";
    $sSqlDescontos .= "                 k02_estorc                                                                                                                                                \n";
    $sSqlDescontos .= "  union all                                                                                                                                                                \n";
    $sSqlDescontos .= "          select k00_numpre,                                                                                                                                               \n";
    $sSqlDescontos .= "                 z01_nome,                                                                                                                                                 \n";
    $sSqlDescontos .= "                 k03_tipo,                                                                                                                                                 \n";
    $sSqlDescontos .= "                 k00_descr,                                                                                                                                                \n";
    $sSqlDescontos .= "                 v03_codigo,                                                                                                                                               \n";
    $sSqlDescontos .= "                 v03_descr,                                                                                                                                                \n";
    $sSqlDescontos .= "                 v07_sequencial,                                                                                                                                           \n";
    $sSqlDescontos .= "                 v07_descricao,                                                                                                                                            \n";
    $sSqlDescontos .= "                 k00_receit,                                                                                                                                               \n";
    $sSqlDescontos .= "                 k02_codrec,                                                                                                                                               \n";
    $sSqlDescontos .= "                 k02_estorc,                                                                                                                                               \n";
    $sSqlDescontos .= "                 k02_drecei,                                                                                                                                               \n";
    $sSqlDescontos .= "                 sum(valor_desconto)     as valor_desconto,                                                                                                                \n";
    $sSqlDescontos .= "                 sum(valor_sem_desconto) as valor_sem_desconto,                                                                                                            \n";
    $sSqlDescontos .= "                 sum(valor_pago)         as valor_pago,                                                                                                                    \n";
    $sSqlDescontos .= "                 sum(k00_valor)          as vlrhist,                                                                                                                       \n";
    $sSqlDescontos .= "                 sum(corrigido)          as corrigido,                                                                                                                     \n";
    $sSqlDescontos .= "                 sum(juros)              as juros,                                                                                                                         \n";
    $sSqlDescontos .= "                 sum(multa)              as multa                                                                                                                          \n";
    $sSqlDescontos .= "            from ( select arrepaga.k00_numpre,                                                                                                                             \n";
    $sSqlDescontos .= "                          cgm.z01_nome,                                                                                                                                    \n";
    $sSqlDescontos .= "                          arretipo.k03_tipo,                                                                                                                               \n";
    $sSqlDescontos .= "                          arretipo.k00_descr,                                                                                                                              \n";
    $sSqlDescontos .= "                          proced.v03_codigo,                                                                                                                               \n";
    $sSqlDescontos .= "                          proced.v03_descr,                                                                                                                                \n";
    $sSqlDescontos .= "                          tipoproced.v07_sequencial,                                                                                                                       \n";
    $sSqlDescontos .= "                          tipoproced.v07_descricao,                                                                                                                        \n";
    $sSqlDescontos .= "                          arrepaga.k00_receit,                                                                                                                             \n";
    $sSqlDescontos .= "                          taborc.k02_codrec,                                                                                                                               \n";
    $sSqlDescontos .= "                          taborc.k02_estorc,                                                                                                                               \n";
    $sSqlDescontos .= "                          tabrec.k02_drecei,                                                                                                                               \n";
    $sSqlDescontos .= "                          ((((termo.v07_vlrcor+termo.v07_vlrjur+termo.v07_vlrmul)*100)/fc_iif((termo.v07_valor=0::float8), 1::float8, termo.v07_valor))-100) as percdes,                                                            \n";
    $sSqlDescontos .= "                          ((((((termo.v07_vlrcor+termo.v07_vlrjur+termo.v07_vlrmul)*100)/fc_iif((termo.v07_valor=0::float8), 1::float8, termo.v07_valor))-100)/100)* arrepaga.k00_valor) * -1 as valor_desconto,                    \n";
    $sSqlDescontos .= "                          arrepaga.k00_valor as valor_pago,                                                                                                                                                                         \n";
    $sSqlDescontos .= "                          ((((((termo.v07_vlrcor+termo.v07_vlrjur+termo.v07_vlrmul)*100)/fc_iif((termo.v07_valor=0::float8), 1::float8, termo.v07_valor))-100)/100)* arrepaga.k00_valor)+arrepaga.k00_valor as valor_sem_desconto,  \n";
    $sSqlDescontos .= "                          arrecant.k00_valor,                                                                                                                              \n";
    $sSqlDescontos .= "                          fc_corre( arrecant.k00_receit,                                                                                                                   \n";
    $sSqlDescontos .= "                                    arrecant.k00_dtvenc,                                                                                                                   \n";
    $sSqlDescontos .= "                                    arrecant.k00_valor,                                                                                                                    \n";
    $sSqlDescontos .= "                                    arrepaga.k00_dtpaga,                                                                                                                   \n";
    $sSqlDescontos .= "                                    cast(extract( year from arrepaga.k00_dtpaga ) as integer),                                                                             \n";
    $sSqlDescontos .= "                                    arrepaga.k00_dtpaga ) as corrigido,                                                                                                    \n";
    $sSqlDescontos .= "                                    ( arrecant.k00_valor * coalesce( fc_juros( arrecant.k00_receit,                                                                        \n";
    $sSqlDescontos .= "                                                                                     arrecant.k00_dtvenc,                                                                  \n";
    $sSqlDescontos .= "                                                                                     arrepaga.k00_dtpaga,                                                                  \n";
    $sSqlDescontos .= "                                                                                     arrepaga.k00_dtpaga,                                                                  \n";
    $sSqlDescontos .= "                                                                                     false,                                                                                \n";
    $sSqlDescontos .= "                                                                                     cast(extract( year from arrepaga.k00_dtpaga ) as integer)                             \n";
    $sSqlDescontos .= "                                                                                   ),0)) as juros,                                                                         \n";
    $sSqlDescontos .= "                                    ( arrecant.k00_valor * coalesce( fc_multa( arrecant.k00_receit,                                                                        \n";
    $sSqlDescontos .= "                                                                                     arrecant.k00_dtvenc,                                                                  \n";
    $sSqlDescontos .= "                                                                                     arrepaga.k00_dtpaga,                                                                  \n";
    $sSqlDescontos .= "                                                                                     arrecant.k00_dtoper,                                                                  \n";
    $sSqlDescontos .= "                                                                                     cast(extract( year from arrepaga.k00_dtpaga ) as integer)                             \n";
    $sSqlDescontos .= "                                                                                   ),0))  as multa                                                                         \n";
    $sSqlDescontos .= "                     from termo                                                                                                                                            \n";
    $sSqlDescontos .= "                          left  join w_origem_dividas on w_origem_dividas.parcel = termo.v07_parcel                                                                        \n";
    $sSqlDescontos .= "                          inner join arrepaga   on arrepaga.k00_numpre = termo.v07_numpre                                                                                  \n";
    $sSqlDescontos .= "                          left join arrecant   on arrecant.k00_numpre = arrepaga.k00_numpre                                                                                \n";
    $sSqlDescontos .= "                                               and arrecant.k00_numpar = arrepaga.k00_numpar                                                                               \n";
    $sSqlDescontos .= "                                               and arrecant.k00_receit = arrepaga.k00_receit                                                                               \n";
    $sSqlDescontos .= "                          left join arretipo   on arretipo.k00_tipo   = arrecant.k00_tipo                                                                                  \n";
    $sSqlDescontos .= "                          left  join divida     on divida.v01_numpre   = arrecant.k00_numpre                                                                               \n";
    $sSqlDescontos .= "                                               and divida.v01_numpar   = arrecant.k00_numpar                                                                               \n";
    $sSqlDescontos .= "                          left  join proced     on proced.v03_codigo   = divida.v01_proced                                                                                 \n";
    $sSqlDescontos .= "                          left  join tipoproced on tipoproced.v07_sequencial = proced.v03_tributaria                                                                       \n";
    $sSqlDescontos .= "                          inner join tabrec     on tabrec.k02_codigo   = arrepaga.k00_receit                                                                               \n";
    $sSqlDescontos .= "                                               and k02_tabrectipo not in (2,3,5)                                                                                           \n";
    $sSqlDescontos .= "                          inner join taborc     on taborc.k02_codigo   = arrepaga.k00_receit                                                                               \n";
    $sSqlDescontos .= "                                               and taborc.k02_anousu   = {$iAnoUsu}                                                                                        \n";
    $sSqlDescontos .= "                          inner join cgm        on cgm.z01_numcgm      = arrepaga.k00_numcgm                                                                               \n";
    $sSqlDescontos .= "                    where arrepaga.k00_dtpaga between '{$dDataInicial}' and '{$dDataFinal}'                                                                                \n";
    $sSqlDescontos .= "                      and termo.v07_instit = {$iInstituicao}                                                                                                               \n";
    $sSqlDescontos .= "                      and ((((termo.v07_vlrcor+termo.v07_vlrjur+termo.v07_vlrmul)*100)/fc_iif((termo.v07_valor=0::float8), 1::float8, termo.v07_valor))-100) is not null   \n";
    $sSqlDescontos .= "                 ) as y                                                                                                                                                    \n";
    $sSqlDescontos .= "        group by k00_numpre,                                                                                                                                               \n";
    $sSqlDescontos .= "                 z01_nome,                                                                                                                                                 \n";
    $sSqlDescontos .= "                 k03_tipo,                                                                                                                                                 \n";
    $sSqlDescontos .= "                 k00_descr,                                                                                                                                                \n";
    $sSqlDescontos .= "                 v03_codigo,                                                                                                                                               \n";
    $sSqlDescontos .= "                 v03_descr,                                                                                                                                                \n";
    $sSqlDescontos .= "                 v07_sequencial,                                                                                                                                           \n";
    $sSqlDescontos .= "                 v07_descricao,                                                                                                                                            \n";
    $sSqlDescontos .= "                 k00_receit,                                                                                                                                               \n";
    $sSqlDescontos .= "                 k02_codrec,                                                                                                                                               \n";
    $sSqlDescontos .= "                 k02_drecei,                                                                                                                                               \n";
    $sSqlDescontos .= "                 k02_estorc                                                                                                                                                \n";
    $sSqlDescontos .= "      ) as x                                                                                                                                                               \n";
    $sSqlDescontos .= "  group by k00_numpre,                                                                                                                                                     \n";
    $sSqlDescontos .= "           z01_nome,                                                                                                                                                       \n";
    $sSqlDescontos .= "           origem,                                                                                                                                                         \n";
    $sSqlDescontos .= "           k03_tipo,                                                                                                                                                       \n";
    $sSqlDescontos .= "           k00_descr,                                                                                                                                                      \n";
    $sSqlDescontos .= "           v03_codigo,                                                                                                                                                     \n";
    $sSqlDescontos .= "           v03_descr,                                                                                                                                                      \n";
    $sSqlDescontos .= "           v07_sequencial,                                                                                                                                                 \n";
    $sSqlDescontos .= "           v07_descricao,                                                                                                                                                  \n";
    $sSqlDescontos .= "           k00_receit,                                                                                                                                                     \n";
    $sSqlDescontos .= "           k02_codrec,                                                                                                                                                     \n";
    $sSqlDescontos .= "           k02_drecei,                                                                                                                                                     \n";
    $sSqlDescontos .= "           k02_estorc                                                                                                                                                      \n";
    $sSqlDescontos .= "    having abs(sum(valor_desconto)) > 0                                                                                                                                    \n";
    $sSqlDescontos .= "  order by z01_nome                                                                                                                                                        \n";
    
    return $sSqlDescontos;

  }
  
  function sql_queryDescontoConcedidoPorRegraAgrupado($dDataInicial, $dDataFinal) {
    
    $sSqlDescontos  = "select receitorcamentoestrutural as receita_orcamento,                                   ";
    $sSqlDescontos .= "       receit           as receita_tesouraria,                                           ";
    $sSqlDescontos .= "       descrreceit      as descricao_receita_tesouraria,                                 ";
    $sSqlDescontos .= "       sum(juros)       as juros,                                                        ";
    $sSqlDescontos .= "       sum(multa)       as multa,                                                        ";
    $sSqlDescontos .= "       sum(desconto)    as desconto,                                                     ";
    $sSqlDescontos .= "       sum(vlrhist)     as valor_historico,                                              ";
    $sSqlDescontos .= "       sum(vlrcorr)     as valor_corrigido,                                              ";
    $sSqlDescontos .= "       sum(valor_pagar) as valor_pagar,                                                  ";
    $sSqlDescontos .= "       sum(valor_pago)  as valor_pago,                                                   ";
    $sSqlDescontos .= "       sum(desconto)    as desconto,                                                     ";
    $sSqlDescontos .= "       sum(total)       as total                                                         ";
    $sSqlDescontos .= "  from ( ". $this->sql_queryDescontoConcedidoPorRegra($dDataInicial, $dDataFinal) . " )  ";
    $sSqlDescontos .= "    as descontos                                                                         ";
    $sSqlDescontos .= " group by receitorcamentoestrutural,                                                     ";
    $sSqlDescontos .= "          receit,                                                                        ";
    $sSqlDescontos .= "          descrreceit                                                                    ";
    $sSqlDescontos .= " order by receitorcamentoestrutural,                                                     ";
    $sSqlDescontos .= "          receit,                                                                        ";
    $sSqlDescontos .= "          descrreceit                                                                    ";
    
    return $sSqlDescontos;
    
  }
  
  function sql_queryPagamentosPorPeriodo($dDataInicial, $dDataFinal) {    

    $iInstituicao = db_getsession('DB_instit');
    $iAnoUsu      = db_getsession('DB_anousu');
    
    $sSql  = "select receita_orcamento,                                                                       \n";
    $sSql .= "       receita_tesouraria,                                                                      \n";
    $sSql .= "       descricao_receita,                                                                       \n";
    $sSql .= "       round( sum (juros), 2)                                       as juros,                   \n";
    $sSql .= "       round( sum (multas), 2)                                      as multas,                  \n";
    $sSql .= "       round( sum ((valor_historico * percentual_origem) / 100), 2) as valor_historico,         \n";
    $sSql .= "       round( sum ((valor_corrigido * percentual_origem) / 100), 2) as valor_corrigido,         \n";
    $sSql .= "       round( sum ((valor_desconto * percentual_origem) / 100), 2)  as valor_desconto           \n";
    $sSql .= "                                                                                                \n";
    $sSql .= "  from (                                                                                        \n";
    $sSql .= "        select                                                                                  \n";
    $sSql .= "               taborc.k02_estorc  as receita_orcamento,                                         \n";
    $sSql .= "               tabrec.k02_codigo  as receita_tesouraria,                                        \n";
    $sSql .= "               tabrec.k02_descr   as descricao_receita,                                         \n";
    $sSql .= "               arrepaga.k00_valor as valor_historico,                                           \n";
    $sSql .= "               0                  as valor_corrigido,                                           \n";
    $sSql .= "               0                  as juros,                                                     \n";
    $sSql .= "               0                  as multas,                                                    \n";
    $sSql .= "               0                  as valor_desconto,                                            \n";
    $sSql .= "               case                                                                             \n";
    $sSql .= "                 when arrematric.k00_numpre is not null then arrematric.k00_perc                \n";
    $sSql .= "                 when arreinscr.k00_numpre  is not null then arreinscr.k00_perc                 \n";
    $sSql .= "                 else 100                                                                       \n";
    $sSql .= "               end as percentual_origem                                                         \n";
    $sSql .= "          from arrepaga                                                                         \n";
    $sSql .= "                                                                                                \n";
    $sSql .= "         inner join arreinstit on arreinstit.k00_numpre = arrepaga.k00_numpre                   \n";
    $sSql .= "                              and arreinstit.k00_instit = {$iInstituicao}                       \n";
    $sSql .= "         inner join tabrec     on tabrec.k02_codigo     = arrepaga.k00_receit                   \n";
    $sSql .= "         inner join taborc     on taborc.k02_codigo     = arrepaga.k00_receit                   \n";
    $sSql .= "                              and taborc.k02_anousu     = {$iAnoUsu}                            \n";
    $sSql .= "         left  join arrematric on arrematric.k00_numpre = arrepaga.k00_numpre                   \n";
    $sSql .= "         left  join arreinscr  on arreinscr.k00_numpre  = arrepaga.k00_numpre                   \n";
    $sSql .= "         where arrepaga.k00_dtpaga between '{$dDataInicial}' and '{$dDataFinal}'                \n";
    $sSql .= "           ) as pagamentos 																					                            \n";
    $sSql .= "group by receita_orcamento,                                                                     \n";
    $sSql .= "         receita_tesouraria,                                                                    \n";
    $sSql .= "         descricao_receita                                                                      \n";
    
    return $sSql;
    
  }
}
?>