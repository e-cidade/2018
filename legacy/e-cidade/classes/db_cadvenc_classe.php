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

//MODULO: issqn
//CLASSE DA ENTIDADE cadvenc
class cl_cadvenc {
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
   var $q82_codigo = 0;
   var $q82_parc = 0;
   var $q82_venc_dia = null;
   var $q82_venc_mes = null;
   var $q82_venc_ano = null;
   var $q82_venc = null;
   var $q82_desc = null;
   var $q82_perc = 0;
   var $q82_hist = 0;
   var $q82_calculaparcvenc = 'f';
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 q82_codigo = int4 = codigo do vencimento
                 q82_parc = int4 = parcela
                 q82_venc = date = data do vencimento da parcela
                 q82_desc = varchar(20) = descricao da parcela
                 q82_perc = float8 = percentual da parcela
                 q82_hist = int4 = historico de calculo desta parcela
                 q82_calculaparcvenc = bool = Calcula Parcela Vencida
                 ";
   //funcao construtor da classe
   function cl_cadvenc() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cadvenc");
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
       $this->q82_codigo = ($this->q82_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["q82_codigo"]:$this->q82_codigo);
       $this->q82_parc = ($this->q82_parc == ""?@$GLOBALS["HTTP_POST_VARS"]["q82_parc"]:$this->q82_parc);
       if($this->q82_venc == ""){
         $this->q82_venc_dia = ($this->q82_venc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["q82_venc_dia"]:$this->q82_venc_dia);
         $this->q82_venc_mes = ($this->q82_venc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["q82_venc_mes"]:$this->q82_venc_mes);
         $this->q82_venc_ano = ($this->q82_venc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["q82_venc_ano"]:$this->q82_venc_ano);
         if($this->q82_venc_dia != ""){
            $this->q82_venc = $this->q82_venc_ano."-".$this->q82_venc_mes."-".$this->q82_venc_dia;
         }
       }
       $this->q82_desc = ($this->q82_desc == ""?@$GLOBALS["HTTP_POST_VARS"]["q82_desc"]:$this->q82_desc);
       $this->q82_perc = ($this->q82_perc == ""?@$GLOBALS["HTTP_POST_VARS"]["q82_perc"]:$this->q82_perc);
       $this->q82_hist = ($this->q82_hist == ""?@$GLOBALS["HTTP_POST_VARS"]["q82_hist"]:$this->q82_hist);
       $this->q82_calculaparcvenc = ($this->q82_calculaparcvenc == "f"?@$GLOBALS["HTTP_POST_VARS"]["q82_calculaparcvenc"]:$this->q82_calculaparcvenc);
     }else{
       $this->q82_codigo = ($this->q82_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["q82_codigo"]:$this->q82_codigo);
       $this->q82_parc = ($this->q82_parc == ""?@$GLOBALS["HTTP_POST_VARS"]["q82_parc"]:$this->q82_parc);
     }
   }
   // funcao para inclusao
   function incluir ($q82_codigo,$q82_parc){
      $this->atualizacampos();
     if($this->q82_venc == null ){
       $this->erro_sql = " Campo data do vencimento da parcela nao Informado.";
       $this->erro_campo = "q82_venc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q82_desc == null ){
       $this->erro_sql = " Campo descricao da parcela nao Informado.";
       $this->erro_campo = "q82_desc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q82_perc == null ){
       $this->erro_sql = " Campo percentual da parcela nao Informado.";
       $this->erro_campo = "q82_perc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q82_hist == null ){
       $this->erro_sql = " Campo historico de calculo desta parcela nao Informado.";
       $this->erro_campo = "q82_hist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q82_calculaparcvenc == null ){
       $this->erro_sql = " Campo Calcula Parcela Vencida nao Informado.";
       $this->erro_campo = "q82_calculaparcvenc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->q82_codigo = $q82_codigo;
       $this->q82_parc = $q82_parc;
     if(($this->q82_codigo == null) || ($this->q82_codigo == "") ){
       $this->erro_sql = " Campo q82_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->q82_parc == null) || ($this->q82_parc == "") ){
       $this->erro_sql = " Campo q82_parc nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cadvenc(
                                       q82_codigo
                                      ,q82_parc
                                      ,q82_venc
                                      ,q82_desc
                                      ,q82_perc
                                      ,q82_hist
                                      ,q82_calculaparcvenc
                       )
                values (
                                $this->q82_codigo
                               ,$this->q82_parc
                               ,".($this->q82_venc == "null" || $this->q82_venc == ""?"null":"'".$this->q82_venc."'")."
                               ,'$this->q82_desc'
                               ,$this->q82_perc
                               ,$this->q82_hist
                               ,'$this->q82_calculaparcvenc'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->q82_codigo."-".$this->q82_parc) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->q82_codigo."-".$this->q82_parc) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q82_codigo."-".$this->q82_parc;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->q82_codigo,$this->q82_parc));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,264,'$this->q82_codigo','I')");
       $resac = db_query("insert into db_acountkey values($acount,265,'$this->q82_parc','I')");
       $resac = db_query("insert into db_acount values($acount,53,264,'','".AddSlashes(pg_result($resaco,0,'q82_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,53,265,'','".AddSlashes(pg_result($resaco,0,'q82_parc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,53,266,'','".AddSlashes(pg_result($resaco,0,'q82_venc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,53,267,'','".AddSlashes(pg_result($resaco,0,'q82_desc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,53,268,'','".AddSlashes(pg_result($resaco,0,'q82_perc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,53,269,'','".AddSlashes(pg_result($resaco,0,'q82_hist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,53,11132,'','".AddSlashes(pg_result($resaco,0,'q82_calculaparcvenc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($q82_codigo=null,$q82_parc=null) {
      $this->atualizacampos();
     $sql = " update cadvenc set ";
     $virgula = "";
     if(trim($this->q82_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q82_codigo"])){
       $sql  .= $virgula." q82_codigo = $this->q82_codigo ";
       $virgula = ",";
       if(trim($this->q82_codigo) == null ){
         $this->erro_sql = " Campo codigo do vencimento nao Informado.";
         $this->erro_campo = "q82_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q82_parc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q82_parc"])){
       $sql  .= $virgula." q82_parc = $this->q82_parc ";
       $virgula = ",";
       if(trim($this->q82_parc) == null ){
         $this->erro_sql = " Campo parcela nao Informado.";
         $this->erro_campo = "q82_parc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q82_venc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q82_venc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["q82_venc_dia"] !="") ){
       $sql  .= $virgula." q82_venc = '$this->q82_venc' ";
       $virgula = ",";
       if(trim($this->q82_venc) == null ){
         $this->erro_sql = " Campo data do vencimento da parcela nao Informado.";
         $this->erro_campo = "q82_venc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["q82_venc_dia"])){
         $sql  .= $virgula." q82_venc = null ";
         $virgula = ",";
         if(trim($this->q82_venc) == null ){
           $this->erro_sql = " Campo data do vencimento da parcela nao Informado.";
           $this->erro_campo = "q82_venc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->q82_desc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q82_desc"])){
       $sql  .= $virgula." q82_desc = '$this->q82_desc' ";
       $virgula = ",";
       if(trim($this->q82_desc) == null ){
         $this->erro_sql = " Campo descricao da parcela nao Informado.";
         $this->erro_campo = "q82_desc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q82_perc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q82_perc"])){
       $sql  .= $virgula." q82_perc = $this->q82_perc ";
       $virgula = ",";
       if(trim($this->q82_perc) == null ){
         $this->erro_sql = " Campo percentual da parcela nao Informado.";
         $this->erro_campo = "q82_perc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q82_hist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q82_hist"])){
       $sql  .= $virgula." q82_hist = $this->q82_hist ";
       $virgula = ",";
       if(trim($this->q82_hist) == null ){
         $this->erro_sql = " Campo historico de calculo desta parcela nao Informado.";
         $this->erro_campo = "q82_hist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q82_calculaparcvenc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q82_calculaparcvenc"])){
       $sql  .= $virgula." q82_calculaparcvenc = '$this->q82_calculaparcvenc' ";
       $virgula = ",";
       if(trim($this->q82_calculaparcvenc) == null ){
         $this->erro_sql = " Campo Calcula Parcela Vencida nao Informado.";
         $this->erro_campo = "q82_calculaparcvenc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($q82_codigo!=null){
       $sql .= " q82_codigo = $this->q82_codigo";
     }
     if($q82_parc!=null){
       $sql .= " and  q82_parc = $this->q82_parc";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->q82_codigo,$this->q82_parc));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,264,'$this->q82_codigo','A')");
         $resac = db_query("insert into db_acountkey values($acount,265,'$this->q82_parc','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q82_codigo"]) || $this->q82_codigo != "")
           $resac = db_query("insert into db_acount values($acount,53,264,'".AddSlashes(pg_result($resaco,$conresaco,'q82_codigo'))."','$this->q82_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q82_parc"]) || $this->q82_parc != "")
           $resac = db_query("insert into db_acount values($acount,53,265,'".AddSlashes(pg_result($resaco,$conresaco,'q82_parc'))."','$this->q82_parc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q82_venc"]) || $this->q82_venc != "")
           $resac = db_query("insert into db_acount values($acount,53,266,'".AddSlashes(pg_result($resaco,$conresaco,'q82_venc'))."','$this->q82_venc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q82_desc"]) || $this->q82_desc != "")
           $resac = db_query("insert into db_acount values($acount,53,267,'".AddSlashes(pg_result($resaco,$conresaco,'q82_desc'))."','$this->q82_desc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q82_perc"]) || $this->q82_perc != "")
           $resac = db_query("insert into db_acount values($acount,53,268,'".AddSlashes(pg_result($resaco,$conresaco,'q82_perc'))."','$this->q82_perc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q82_hist"]) || $this->q82_hist != "")
           $resac = db_query("insert into db_acount values($acount,53,269,'".AddSlashes(pg_result($resaco,$conresaco,'q82_hist'))."','$this->q82_hist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q82_calculaparcvenc"]) || $this->q82_calculaparcvenc != "")
           $resac = db_query("insert into db_acount values($acount,53,11132,'".AddSlashes(pg_result($resaco,$conresaco,'q82_calculaparcvenc'))."','$this->q82_calculaparcvenc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q82_codigo."-".$this->q82_parc;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q82_codigo."-".$this->q82_parc;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q82_codigo."-".$this->q82_parc;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($q82_codigo=null,$q82_parc=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($q82_codigo,$q82_parc));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,264,'$q82_codigo','E')");
         $resac = db_query("insert into db_acountkey values($acount,265,'$q82_parc','E')");
         $resac = db_query("insert into db_acount values($acount,53,264,'','".AddSlashes(pg_result($resaco,$iresaco,'q82_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,53,265,'','".AddSlashes(pg_result($resaco,$iresaco,'q82_parc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,53,266,'','".AddSlashes(pg_result($resaco,$iresaco,'q82_venc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,53,267,'','".AddSlashes(pg_result($resaco,$iresaco,'q82_desc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,53,268,'','".AddSlashes(pg_result($resaco,$iresaco,'q82_perc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,53,269,'','".AddSlashes(pg_result($resaco,$iresaco,'q82_hist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,53,11132,'','".AddSlashes(pg_result($resaco,$iresaco,'q82_calculaparcvenc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from cadvenc
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q82_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q82_codigo = $q82_codigo ";
        }
        if($q82_parc != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q82_parc = $q82_parc ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q82_codigo."-".$q82_parc;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q82_codigo."-".$q82_parc;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q82_codigo."-".$q82_parc;
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
        $this->erro_sql   = "Record Vazio na Tabela:cadvenc";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $q82_codigo=null,$q82_parc=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from cadvenc ";
     $sql .= "      inner join cadvencdesc  on  cadvencdesc.q92_codigo = cadvenc.q82_codigo";
     $sql .= "      inner join histcalc  on  histcalc.k01_codigo = cadvenc.q82_hist";
     $sql .= "      inner join histcalc  as a on   a.k01_codigo = cadvencdesc.q92_hist";
     $sql .= "      inner join arretipo  on  arretipo.k00_tipo = cadvencdesc.q92_tipo";
     $sql2 = "";
     if($dbwhere==""){
       if($q82_codigo!=null ){
         $sql2 .= " where cadvenc.q82_codigo = $q82_codigo ";
       }
       if($q82_parc!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " cadvenc.q82_parc = $q82_parc ";
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
   function sql_query_file ( $q82_codigo=null,$q82_parc=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from cadvenc ";
     $sql2 = "";
     if($dbwhere==""){
       if($q82_codigo!=null ){
         $sql2 .= " where cadvenc.q82_codigo = $q82_codigo ";
       }
       if($q82_parc!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " cadvenc.q82_parc = $q82_parc ";
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
   function alterar_alt ($q82_codigo=null,$q82_parc=null,$dbwhere="") {
      $this->atualizacampos();
     $sql = " update cadvenc set ";
     $virgula = "";
     if(trim($this->q82_venc)!=""){
       $sql  .= $virgula." q82_venc = '$this->q82_venc' ";
       $virgula = ",";
     }
     if(trim($this->q82_desc)!=""){
       $sql  .= $virgula." q82_desc = '$this->q82_desc' ";
       $virgula = ",";
     }
     if(trim($this->q82_perc)!=""){
       $sql  .= $virgula." q82_perc = $this->q82_perc ";
       $virgula = ",";
     }
     if(trim($this->q82_hist)!=""){
       $sql  .= $virgula." q82_hist = $this->q82_hist ";
       $virgula = ",";
     }
     $sql2 = "";
     if($dbwhere==""){
       if($q82_codigo!=null ){
         $sql2 .= " where cadvenc.q82_codigo = $q82_codigo ";
       }
       if($q82_parc!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " cadvenc.q82_parc = $q82_parc ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->q82_codigo,$this->q82_parc));
     if($this->numrows>0){       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountkey values($acount,264,'$this->q82_codigo','A')");
       $resac = db_query("insert into db_acountkey values($acount,265,'$this->q82_parc','A')");
       if(isset($GLOBALS["HTTP_POST_VARS"]["q82_codigo"]))
         $resac = db_query("insert into db_acount values($acount,53,264,'".pg_result($resaco,0,'q82_codigo')."','$this->q82_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["q82_parc"]))
         $resac = db_query("insert into db_acount values($acount,53,265,'".pg_result($resaco,0,'q82_parc')."','$this->q82_parc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["q82_venc"]))
         $resac = db_query("insert into db_acount values($acount,53,266,'".pg_result($resaco,0,'q82_venc')."','$this->q82_venc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["q82_desc"]))
         $resac = db_query("insert into db_acount values($acount,53,267,'".pg_result($resaco,0,'q82_desc')."','$this->q82_desc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["q82_perc"]))
         $resac = db_query("insert into db_acount values($acount,53,268,'".pg_result($resaco,0,'q82_perc')."','$this->q82_perc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["q82_hist"]))
         $resac = db_query("insert into db_acount values($acount,53,269,'".pg_result($resaco,0,'q82_hist')."','$this->q82_hist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     $result = @db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q82_codigo."-".$this->q82_parc;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q82_codigo."-".$this->q82_parc;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração Efetivada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q82_codigo."-".$this->q82_parc;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       }
     }
   }

   function sql_query_file_exercicio_cissqn($sWhere = ""){

     $sql = "select *       ";
     $sql .= " from cadvenc ";
     $sql .= "      inner join cissqn on cissqn.q04_anousu = extract(year from cadvenc.q82_venc)";

     if($sWhere != ""){
       $sql .= " where $sWhere ";
 }

     return $sql;
  }
}
?>