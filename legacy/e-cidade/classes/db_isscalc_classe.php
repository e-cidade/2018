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
//CLASSE DA ENTIDADE isscalc
class cl_isscalc {
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
   var $q01_anousu = 0;
   var $q01_inscr = 0;
   var $q01_cadcal = 0;
   var $q01_recei = 0;
   var $q01_numpre = 0;
   var $q01_valor = 0;
   var $q01_manual = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 q01_anousu = int4 = ano
                 q01_inscr = int4 = inscricao
                 q01_cadcal = int4 = codigo do calculo
                 q01_recei = int4 = codigo da receita
                 q01_numpre = int4 = numpre
                 q01_valor = float8 = valor
                 q01_manual = text = Log do calculo
                 ";
   //funcao construtor da classe
   function cl_isscalc() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("isscalc");
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
       $this->q01_anousu = ($this->q01_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["q01_anousu"]:$this->q01_anousu);
       $this->q01_inscr = ($this->q01_inscr == ""?@$GLOBALS["HTTP_POST_VARS"]["q01_inscr"]:$this->q01_inscr);
       $this->q01_cadcal = ($this->q01_cadcal == ""?@$GLOBALS["HTTP_POST_VARS"]["q01_cadcal"]:$this->q01_cadcal);
       $this->q01_recei = ($this->q01_recei == ""?@$GLOBALS["HTTP_POST_VARS"]["q01_recei"]:$this->q01_recei);
       $this->q01_numpre = ($this->q01_numpre == ""?@$GLOBALS["HTTP_POST_VARS"]["q01_numpre"]:$this->q01_numpre);
       $this->q01_valor = ($this->q01_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["q01_valor"]:$this->q01_valor);
       $this->q01_manual = ($this->q01_manual == ""?@$GLOBALS["HTTP_POST_VARS"]["q01_manual"]:$this->q01_manual);
     }else{
       $this->q01_anousu = ($this->q01_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["q01_anousu"]:$this->q01_anousu);
       $this->q01_inscr = ($this->q01_inscr == ""?@$GLOBALS["HTTP_POST_VARS"]["q01_inscr"]:$this->q01_inscr);
       $this->q01_cadcal = ($this->q01_cadcal == ""?@$GLOBALS["HTTP_POST_VARS"]["q01_cadcal"]:$this->q01_cadcal);
       $this->q01_recei = ($this->q01_recei == ""?@$GLOBALS["HTTP_POST_VARS"]["q01_recei"]:$this->q01_recei);
       $this->q01_numpre = ($this->q01_numpre == ""?@$GLOBALS["HTTP_POST_VARS"]["q01_numpre"]:$this->q01_numpre);
     }
   }
   // funcao para inclusao
   function incluir ($q01_anousu,$q01_inscr,$q01_cadcal,$q01_recei,$q01_numpre){
      $this->atualizacampos();
     if($this->q01_valor == null ){
       $this->erro_sql = " Campo valor nao Informado.";
       $this->erro_campo = "q01_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q01_manual == null ){
       $this->erro_sql = " Campo Log do calculo nao Informado.";
       $this->erro_campo = "q01_manual";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->q01_anousu = $q01_anousu;
       $this->q01_inscr = $q01_inscr;
       $this->q01_cadcal = $q01_cadcal;
       $this->q01_recei = $q01_recei;
       $this->q01_numpre = $q01_numpre;
     if(($this->q01_anousu == null) || ($this->q01_anousu == "") ){
       $this->erro_sql = " Campo q01_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->q01_inscr == null) || ($this->q01_inscr == "") ){
       $this->erro_sql = " Campo q01_inscr nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->q01_cadcal == null) || ($this->q01_cadcal == "") ){
       $this->erro_sql = " Campo q01_cadcal nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->q01_recei == null) || ($this->q01_recei == "") ){
       $this->erro_sql = " Campo q01_recei nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->q01_numpre == null) || ($this->q01_numpre == "") ){
       $this->erro_sql = " Campo q01_numpre nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into isscalc(
                                       q01_anousu
                                      ,q01_inscr
                                      ,q01_cadcal
                                      ,q01_recei
                                      ,q01_numpre
                                      ,q01_valor
                                      ,q01_manual
                       )
                values (
                                $this->q01_anousu
                               ,$this->q01_inscr
                               ,$this->q01_cadcal
                               ,$this->q01_recei
                               ,$this->q01_numpre
                               ,$this->q01_valor
                               ,'$this->q01_manual'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->q01_anousu."-".$this->q01_inscr."-".$this->q01_cadcal."-".$this->q01_recei."-".$this->q01_numpre) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->q01_anousu."-".$this->q01_inscr."-".$this->q01_cadcal."-".$this->q01_recei."-".$this->q01_numpre) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q01_anousu."-".$this->q01_inscr."-".$this->q01_cadcal."-".$this->q01_recei."-".$this->q01_numpre;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->q01_anousu,$this->q01_inscr,$this->q01_cadcal,$this->q01_recei,$this->q01_numpre));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,310,'$this->q01_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,311,'$this->q01_inscr','I')");
       $resac = db_query("insert into db_acountkey values($acount,312,'$this->q01_cadcal','I')");
       $resac = db_query("insert into db_acountkey values($acount,313,'$this->q01_recei','I')");
       $resac = db_query("insert into db_acountkey values($acount,314,'$this->q01_numpre','I')");
       $resac = db_query("insert into db_acount values($acount,61,310,'','".AddSlashes(pg_result($resaco,0,'q01_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,61,311,'','".AddSlashes(pg_result($resaco,0,'q01_inscr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,61,312,'','".AddSlashes(pg_result($resaco,0,'q01_cadcal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,61,313,'','".AddSlashes(pg_result($resaco,0,'q01_recei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,61,314,'','".AddSlashes(pg_result($resaco,0,'q01_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,61,315,'','".AddSlashes(pg_result($resaco,0,'q01_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,61,6455,'','".AddSlashes(pg_result($resaco,0,'q01_manual'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($q01_anousu=null,$q01_inscr=null,$q01_cadcal=null,$q01_recei=null,$q01_numpre=null) {
      $this->atualizacampos();
     $sql = " update isscalc set ";
     $virgula = "";
     if(trim($this->q01_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q01_anousu"])){
       $sql  .= $virgula." q01_anousu = $this->q01_anousu ";
       $virgula = ",";
       if(trim($this->q01_anousu) == null ){
         $this->erro_sql = " Campo ano nao Informado.";
         $this->erro_campo = "q01_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q01_inscr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q01_inscr"])){
       $sql  .= $virgula." q01_inscr = $this->q01_inscr ";
       $virgula = ",";
       if(trim($this->q01_inscr) == null ){
         $this->erro_sql = " Campo inscricao nao Informado.";
         $this->erro_campo = "q01_inscr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q01_cadcal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q01_cadcal"])){
       $sql  .= $virgula." q01_cadcal = $this->q01_cadcal ";
       $virgula = ",";
       if(trim($this->q01_cadcal) == null ){
         $this->erro_sql = " Campo codigo do calculo nao Informado.";
         $this->erro_campo = "q01_cadcal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q01_recei)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q01_recei"])){
       $sql  .= $virgula." q01_recei = $this->q01_recei ";
       $virgula = ",";
       if(trim($this->q01_recei) == null ){
         $this->erro_sql = " Campo codigo da receita nao Informado.";
         $this->erro_campo = "q01_recei";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q01_numpre)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q01_numpre"])){
       $sql  .= $virgula." q01_numpre = $this->q01_numpre ";
       $virgula = ",";
       if(trim($this->q01_numpre) == null ){
         $this->erro_sql = " Campo numpre nao Informado.";
         $this->erro_campo = "q01_numpre";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q01_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q01_valor"])){
       $sql  .= $virgula." q01_valor = $this->q01_valor ";
       $virgula = ",";
       if(trim($this->q01_valor) == null ){
         $this->erro_sql = " Campo valor nao Informado.";
         $this->erro_campo = "q01_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q01_manual)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q01_manual"])){
       $sql  .= $virgula." q01_manual = '$this->q01_manual' ";
       $virgula = ",";
       if(trim($this->q01_manual) == null ){
         $this->erro_sql = " Campo Log do calculo nao Informado.";
         $this->erro_campo = "q01_manual";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($q01_anousu!=null){
       $sql .= " q01_anousu = $this->q01_anousu";
     }
     if($q01_inscr!=null){
       $sql .= " and  q01_inscr = $this->q01_inscr";
     }
     if($q01_cadcal!=null){
       $sql .= " and  q01_cadcal = $this->q01_cadcal";
     }
     if($q01_recei!=null){
       $sql .= " and  q01_recei = $this->q01_recei";
     }
     if($q01_numpre!=null){
       $sql .= " and  q01_numpre = $this->q01_numpre";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->q01_anousu,$this->q01_inscr,$this->q01_cadcal,$this->q01_recei,$this->q01_numpre));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,310,'$this->q01_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,311,'$this->q01_inscr','A')");
         $resac = db_query("insert into db_acountkey values($acount,312,'$this->q01_cadcal','A')");
         $resac = db_query("insert into db_acountkey values($acount,313,'$this->q01_recei','A')");
         $resac = db_query("insert into db_acountkey values($acount,314,'$this->q01_numpre','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q01_anousu"]))
           $resac = db_query("insert into db_acount values($acount,61,310,'".AddSlashes(pg_result($resaco,$conresaco,'q01_anousu'))."','$this->q01_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q01_inscr"]))
           $resac = db_query("insert into db_acount values($acount,61,311,'".AddSlashes(pg_result($resaco,$conresaco,'q01_inscr'))."','$this->q01_inscr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q01_cadcal"]))
           $resac = db_query("insert into db_acount values($acount,61,312,'".AddSlashes(pg_result($resaco,$conresaco,'q01_cadcal'))."','$this->q01_cadcal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q01_recei"]))
           $resac = db_query("insert into db_acount values($acount,61,313,'".AddSlashes(pg_result($resaco,$conresaco,'q01_recei'))."','$this->q01_recei',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q01_numpre"]))
           $resac = db_query("insert into db_acount values($acount,61,314,'".AddSlashes(pg_result($resaco,$conresaco,'q01_numpre'))."','$this->q01_numpre',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q01_valor"]))
           $resac = db_query("insert into db_acount values($acount,61,315,'".AddSlashes(pg_result($resaco,$conresaco,'q01_valor'))."','$this->q01_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q01_manual"]))
           $resac = db_query("insert into db_acount values($acount,61,6455,'".AddSlashes(pg_result($resaco,$conresaco,'q01_manual'))."','$this->q01_manual',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q01_anousu."-".$this->q01_inscr."-".$this->q01_cadcal."-".$this->q01_recei."-".$this->q01_numpre;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q01_anousu."-".$this->q01_inscr."-".$this->q01_cadcal."-".$this->q01_recei."-".$this->q01_numpre;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q01_anousu."-".$this->q01_inscr."-".$this->q01_cadcal."-".$this->q01_recei."-".$this->q01_numpre;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($q01_anousu=null,$q01_inscr=null,$q01_cadcal=null,$q01_recei=null,$q01_numpre=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($q01_anousu,$q01_inscr,$q01_cadcal,$q01_recei,$q01_numpre));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,310,'$q01_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,311,'$q01_inscr','E')");
         $resac = db_query("insert into db_acountkey values($acount,312,'$q01_cadcal','E')");
         $resac = db_query("insert into db_acountkey values($acount,313,'$q01_recei','E')");
         $resac = db_query("insert into db_acountkey values($acount,314,'$q01_numpre','E')");
         $resac = db_query("insert into db_acount values($acount,61,310,'','".AddSlashes(pg_result($resaco,$iresaco,'q01_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,61,311,'','".AddSlashes(pg_result($resaco,$iresaco,'q01_inscr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,61,312,'','".AddSlashes(pg_result($resaco,$iresaco,'q01_cadcal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,61,313,'','".AddSlashes(pg_result($resaco,$iresaco,'q01_recei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,61,314,'','".AddSlashes(pg_result($resaco,$iresaco,'q01_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,61,315,'','".AddSlashes(pg_result($resaco,$iresaco,'q01_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,61,6455,'','".AddSlashes(pg_result($resaco,$iresaco,'q01_manual'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from isscalc
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q01_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q01_anousu = $q01_anousu ";
        }
        if($q01_inscr != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q01_inscr = $q01_inscr ";
        }
        if($q01_cadcal != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q01_cadcal = $q01_cadcal ";
        }
        if($q01_recei != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q01_recei = $q01_recei ";
        }
        if($q01_numpre != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q01_numpre = $q01_numpre ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q01_anousu."-".$q01_inscr."-".$q01_cadcal."-".$q01_recei."-".$q01_numpre;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q01_anousu."-".$q01_inscr."-".$q01_cadcal."-".$q01_recei."-".$q01_numpre;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q01_anousu."-".$q01_inscr."-".$q01_cadcal."-".$q01_recei."-".$q01_numpre;
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
        $this->erro_sql   = "Record Vazio na Tabela:isscalc";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $q01_anousu=null,$q01_inscr=null,$q01_cadcal=null,$q01_recei=null,$q01_numpre=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from isscalc ";
     $sql .= "      inner join issbase  on  issbase.q02_inscr = isscalc.q01_inscr";
     $sql .= "      inner join cadcalc  on  cadcalc.q85_codigo = isscalc.q01_cadcal";
     $sql .= "      inner join tabrec  on  tabrec.k02_codigo = isscalc.q01_recei";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = issbase.q02_numcgm";
     $sql .= "      inner join cadvencdesc  on  cadvencdesc.q92_codigo = cadcalc.q85_codven";
     $sql .= "      inner join forcaldesc  on  forcaldesc.q87_codigo = cadcalc.q85_forcal";
     $sql .= "      inner join tabrecjm  on  tabrecjm.k02_codjm = tabrec.k02_codjm";
     $sql2 = "";
     if($dbwhere==""){
       if($q01_anousu!=null ){
         $sql2 .= " where isscalc.q01_anousu = $q01_anousu ";
       }
       if($q01_inscr!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " isscalc.q01_inscr = $q01_inscr ";
       }
       if($q01_cadcal!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " isscalc.q01_cadcal = $q01_cadcal ";
       }
       if($q01_recei!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " isscalc.q01_recei = $q01_recei ";
       }
       if($q01_numpre!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " isscalc.q01_numpre = $q01_numpre ";
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
   function sql_query_arrecad ( $q01_anousu=null,$q01_inscr=null,$q01_cadcal=null,$q01_recei=null,$q01_numpre=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from isscalc ";
     $sql .= "	inner join arrecad on arrecad.k00_numpre = isscalc.q01_numpre";
     $sql2 = "";
     if($dbwhere==""){
       if($q01_anousu!=null ){
         $sql2 .= " where isscalc.q01_anousu = $q01_anousu ";
       }
       if($q01_inscr!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " isscalc.q01_inscr = $q01_inscr ";
       }
       if($q01_cadcal!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " isscalc.q01_cadcal = $q01_cadcal ";
       }
       if($q01_recei!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " isscalc.q01_recei = $q01_recei ";
       }
       if($q01_numpre!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " isscalc.q01_numpre = $q01_numpre ";
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
   function sql_query_file ( $q01_anousu=null,$q01_inscr=null,$q01_cadcal=null,$q01_recei=null,$q01_numpre=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from isscalc ";
     $sql2 = "";
     if($dbwhere==""){
       if($q01_anousu!=null ){
         $sql2 .= " where isscalc.q01_anousu = $q01_anousu ";
       }
       if($q01_inscr!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " isscalc.q01_inscr = $q01_inscr ";
       }
       if($q01_cadcal!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " isscalc.q01_cadcal = $q01_cadcal ";
       }
       if($q01_recei!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " isscalc.q01_recei = $q01_recei ";
       }
       if($q01_numpre!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " isscalc.q01_numpre = $q01_numpre ";
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

  function sql_queryIssqnVistorias($iAnoCalculo) {

    $sSql  = "select codigo_tipodebito,                                                                                                                  ";
    $sSql .= "       tipodebito,                                                                                                                         ";
    $sSql .= "       codigo_receita,                                                                                                                     ";
    $sSql .= "       receita,                                                                                                                            ";
    $sSql .= "       coalesce(round(sum(valor_calculado), 2), 0.00) as valor_calculado,                                                                  ";
    $sSql .= "       coalesce(round(sum(valor_importado), 2), 0.00) as valor_importado,                                                                  ";
    $sSql .= "       coalesce(round(sum(valor_pago), 2), 0.00)      as valor_pago,                                                                       ";
    $sSql .= "       coalesce(round(sum(valor_cancelado), 2), 0.00) as valor_cancelado,                                                                  ";
    $sSql .= "       coalesce(round(sum(valor_a_pagar), 2), 0.00)   as valor_a_pagar,                                                                    ";
    $sSql .= "       coalesce(round(sum(valor_compensado), 2), 0.00)   as valor_compensado,                                                              ";
    $sSql .= "       sum(quantidade) as quantidade                                                                                                       ";
    $sSql .= "  from (                                                                                                                                   ";
    $sSql .= "        select q01_valor as valor_calculado,                                                                                               ";
    $sSql .= "        (select sum(valor) from (                                                                                                          ";
    $sSql .= "          select sum(k00_valor) as valor from arrecant                                                                                     ";
    $sSql .= "            inner join abatimentoutilizacaodestino on k170_numpre = arrecant.k00_numpre                                                    ";
    $sSql .= "                                                  and k170_numpar = arrecant.k00_numpar                                                    ";
    $sSql .= "                                                  and k170_receit = arrecant.k00_receit                                                    ";
    $sSql .= "            inner join abatimentoutilizacao on k157_sequencial = k170_utilizacao                                                           ";
    $sSql .= "            inner join abatimento on k125_sequencial = k157_abatimento                                                                     ";
    $sSql .= "          where                                                                                                                            ";
    $sSql .= "            arrecant.k00_numpre = isscalc.q01_numpre                                                                                       ";
    $sSql .= "            and arrecant.k00_receit = tabrec.k02_codigo                                                                                    ";
    $sSql .= "            and k125_tipoabatimento = " . Abatimento::TIPO_CREDITO;
    $sSql .= "          union all                                                                                                                        ";
    $sSql .= "          select sum(abatimentoarreckey.k128_valorabatido) as valor from arrecad                                                           ";
    $sSql .= "            inner join arreckey on arreckey.k00_numpre = arrecad.k00_numpre                                                                ";
    $sSql .= "                               and arreckey.k00_numpar = arrecad.k00_numpar                                                                ";
    $sSql .= "                               and arreckey.k00_receit = arrecad.k00_receit                                                                ";
    $sSql .= "            inner join abatimentoarreckey on k128_arreckey = arreckey.k00_sequencial                                                       ";
    $sSql .= "            inner join abatimento on k125_sequencial = k128_abatimento                                                                     ";
    $sSql .= "          where k125_tipoabatimento = " . Abatimento::TIPO_COMPENSACAO;
    $sSql .= "            and arrecad.k00_numpre = isscalc.q01_numpre                                                                                    ";
    $sSql .= "            and arrecad.k00_receit = tabrec.k02_codigo                                                                                     ";
    $sSql .= "        ) as valor) as valor_compensado,                                                                                                   ";
    $sSql .= "               (select sum(k00_valor)                                                                                                      ";
    $sSql .= "                  from arrecad                                                                                                             ";
    $sSql .= "                 where k00_numpre = isscalc.q01_numpre) as valor_a_pagar,                                                                  ";
    $sSql .= "               (select sum(valor) from (                                                                                             ";
    $sSql .= "                select (arrecant.k00_valor) as valor                                                                                            ";
    $sSql .= "                  from arrecant                                                                                                            ";
    $sSql .= "                 where exists (select 1                                                                                                    ";
    $sSql .= "                                 from arrepaga                                                                                             ";
    $sSql .= "                                where                                                                                                      ";
    $sSql .= "                                      k00_numpre = arrecant.k00_numpre                                                                     ";
    $sSql .= "                                  and k00_numpar = arrecant.k00_numpar                                                                     ";
    $sSql .= "                                  and k00_receit = arrecant.k00_receit)                                                                    ";
    $sSql .= "                   and arrecant.k00_numpre =  isscalc.q01_numpre                                                                           ";
    $sSql .= "                   and arrecant.k00_receit = tabrec.k02_codigo                                                              ";
    $sSql .= "                   and not exists( select 1";
    $sSql .= "                     from abatimentoutilizacaodestino";
    $sSql .= "                       inner join abatimentoutilizacao on k157_sequencial = k170_utilizacao";
    $sSql .= "                       inner join abatimento on k125_sequencial = k157_abatimento";
    $sSql .= "                     where k170_numpre = arrecant.k00_numpre";
    $sSql .= "                       and k170_numpar = arrecant.k00_numpar";
    $sSql .= "                       and k170_receit = arrecant.k00_receit";
    $sSql .= "                       and k125_tipoabatimento = " . Abatimento::TIPO_CREDITO;
    $sSql .= "                     limit 1";
    $sSql .= "                  )";
    $sSql .= "           union all ";
    $sSql .= " select sum(abatimentoarreckey.k128_valorabatido) as valor ";
    $sSql .= "   from arrecad ";
    $sSql .= "        inner join arreckey on arrecad.k00_numpre = arreckey.k00_numpre ";
    $sSql .= "                           and arrecad.k00_numpar = arreckey.k00_numpar ";
    $sSql .= "                           and arrecad.k00_receit = arreckey.k00_receit ";
    $sSql .= "        inner join abatimentoarreckey on abatimentoarreckey.k128_arreckey = arreckey.k00_sequencial ";
    $sSql .= "        inner join abatimento on abatimentoarreckey.k128_abatimento = abatimento.k125_sequencial ";
    $sSql .= "  where arrecad.k00_numpre = isscalc.q01_numpre ";
    $sSql .= "    and arrecad.k00_receit = tabrec.k02_codigo ";
    $sSql .= "    and abatimento.k125_tipoabatimento = ".Abatimento::TIPO_PAGAMENTO_PARCIAL;
    $sSql .= "  union all ";
    $sSql .= " select sum(abatimentoarreckey.k128_valorabatido) as valor ";
    $sSql .= "   from arrecant ";
    $sSql .= "        inner join arreckey on arrecant.k00_numpre = arreckey.k00_numpre ";
    $sSql .= "                           and arrecant.k00_numpar = arreckey.k00_numpar ";
    $sSql .= "                           and arrecant.k00_receit = arreckey.k00_receit ";
    $sSql .= "        inner join abatimentoarreckey on abatimentoarreckey.k128_arreckey = arreckey.k00_sequencial ";
    $sSql .= "        inner join abatimento on abatimentoarreckey.k128_abatimento = abatimento.k125_sequencial ";
    $sSql .= "  where arrecant.k00_numpre = isscalc.q01_numpre ";
    $sSql .= "    and arrecant.k00_receit = tabrec.k02_codigo ";
    $sSql .= "    and abatimento.k125_tipoabatimento = ".Abatimento::TIPO_PAGAMENTO_PARCIAL;
    $sSql .= "             ) as y ";
    $sSql .= "               ) as valor_pago,                                                             ";
    $sSql .= "               (select sum(coalesce(arrecant.k00_valor, 2))                                                                                ";
    $sSql .= "                  from arrecant                                                                                                            ";
    $sSql .= "                       inner join cancdebitosreg     on k21_numpre         = k00_numpre                                                    ";
    $sSql .= "                                                    and k21_numpar         = k00_numpar                                                    ";
    $sSql .= "                       inner join cancdebitosprocreg on k24_cancdebitosreg = k21_sequencia                                                 ";
    $sSql .= "                 where k00_numpre = isscalc.q01_numpre) as valor_cancelado,                                                                ";
    $sSql .= "              (select sum(k00_valor)                                                                                                       ";
    $sSql .= "                 from arreold                                                                                                              ";
    $sSql .= "                where k00_numpre in (select distinct k10_numpre                                                                            ";
    $sSql .= "                                       from divold                                                                                         ";
    $sSql .= "                                      where k10_numpre = isscalc.q01_numpre)                                                               ";
    $sSql .= "                  and k00_receit =  tabrec.k02_codigo                                                                                      ";
    $sSql .= "                group by k00_receit) as valor_importado,                                                                                   ";
    $sSql .= "               k02_codigo as codigo_receita,                                                                                               ";
    $sSql .= "               k02_descr  as receita,                                                                                                      ";
    $sSql .= "               q85_codigo as codigo_tipodebito,                                                                                            ";
    $sSql .= "               q85_descr  as tipodebito,                                                                                                   ";
    $sSql .= "               1::numeric as quantidade                                                                                                    ";
    $sSql .= "          from isscalc                                                                                                                     ";
    $sSql .= "               inner join cadcalc  on q85_codigo = q01_cadcal                                                                              ";
    $sSql .= "               inner join tabrec   on k02_codigo = q01_recei                                                                               ";
    $sSql .= "         where q01_anousu = {$iAnoCalculo}                                                                                                 ";
    $sSql .= "           and q01_cadcal = 2                                                                                                              ";
    $sSql .= "         union all                                                                                                                         ";
    $sSql .= "        select round(coalesce(sum(valor_a_pagar), 0) +                                                                                     ";
    $sSql .= "                     coalesce(sum(valor_pago), 0) +                                                                                        ";
    $sSql .= "                     coalesce(sum(valor_cancelado), 0) , 2) as valor_calculado,                                                            ";
    $sSql .= "               round(sum(valor_compensado), 2)  as valor_compensado,                                                                         ";
    $sSql .= "               round(sum(valor_a_pagar), 2)    as valor_a_pagar,                                                                           ";
    $sSql .= "               round(sum(valor_pago), 2)       as valor_pago ,                                                                             ";
    $sSql .= "               round(sum(valor_cancelado), 2)  as valor_cancelado,                                                                         ";
    $sSql .= "               round(sum(valor_importado), 2)  as valor_importado,                                                                         ";
    $sSql .= "               codigo_receita,                                                                                                             ";
    $sSql .= "               k02_descr as receita,                                                                                                       ";
    $sSql .= "               codigo_tipodebito,                                                                                                          ";
    $sSql .= "               k00_descr as tipodebito,                                                                                                    ";
    $sSql .= "               sum(quantidade) as quantidade                                                                                               ";
    $sSql .= "          from (                                                                                                                           ";
    $sSql .= "                select arrecad.k00_valor  as valor_a_pagar,                                                                                ";
    $sSql .= "                (select sum(valor) from (";
    $sSql .= "                  select coalesce(sum(arrecant_pago.k00_valor), 0) AS valor";
    $sSql .= "                  from arrecant as arrecant_pago";
    $sSql .= "                    inner join arrepaga as arrepaga_pago on arrecant_pago.k00_numpre = arrepaga_pago.k00_numpre";
    $sSql .= "                                                        and arrecant_pago.k00_numpar = arrepaga_pago.k00_numpar";
    $sSql .= "                                                        and arrecant_pago.k00_receit = arrepaga_pago.k00_receit";
    $sSql .= "                    inner join abatimentoutilizacaodestino on arrecant_pago.k00_numpre = k170_numpre";
    $sSql .= "                                                        and arrecant_pago.k00_numpar = k170_numpar";
    $sSql .= "                                                        and arrecant_pago.k00_receit = k170_receit";
    $sSql .= "                    inner join abatimentoutilizacao on k157_sequencial = k170_utilizacao";
    $sSql .= "                    inner join abatimento on k125_sequencial = k157_abatimento";
    $sSql .= "                  where arrecant_pago.k00_numpre = vistorianumpre.y69_numpre";
    $sSql .= "                    and (arrecant_pago.k00_receit = arrecant.k00_receit";
    $sSql .= "                         and arrepaga_pago.k00_receit = arrepaga.k00_receit)";
    $sSql .= "                    and k125_tipoabatimento = " . Abatimento::TIPO_CREDITO;
    $sSql .= "                  union all";
    $sSql .= "                  select sum(abatimentoarreckey.k128_valorabatido) as valor";
    $sSql .= "                  from arrecad";
    $sSql .= "                    inner join arreckey on arrecad.k00_numpre = arreckey.k00_numpre";
    $sSql .= "                                       and arrecad.k00_numpar = arreckey.k00_numpar";
    $sSql .= "                                       and arrecad.k00_receit = arreckey.k00_receit";
    $sSql .= "                    inner join abatimentoarreckey on abatimentoarreckey.k128_arreckey = arreckey.k00_sequencial";
    $sSql .= "                    inner join abatimento on abatimentoarreckey.k128_abatimento = abatimento.k125_sequencial";
    $sSql .= "                  where arrecad.k00_numpre = vistorianumpre.y69_numpre";
    $sSql .= "                    and abatimento.k125_tipoabatimento = " . Abatimento::TIPO_COMPENSACAO;
    $sSql .= "                ) as valor ) as valor_compensado,";
    $sSql .= "                       (select sum(valor) from (                                                                                ";
    $sSql .= "                        select (arrecant_pago.k00_valor) as valor                                                                                ";
    $sSql .= "                          from arrecant as arrecant_pago                                                                                   ";
    $sSql .= "                               inner join arrepaga as arrepaga_pago  on arrecant_pago.k00_numpre = arrepaga_pago.k00_numpre                ";
    $sSql .= "                                                                    and arrecant_pago.k00_numpar = arrepaga_pago.k00_numpar                ";
    $sSql .= "                                                                    and arrecant_pago.k00_receit = arrepaga_pago.k00_receit                ";
    $sSql .= "                         where arrecant_pago.k00_numpre = vistorianumpre.y69_numpre                                                        ";
    $sSql .= "                           and (arrecant_pago.k00_receit  = arrecant.k00_receit and arrepaga_pago.k00_receit  = arrepaga.k00_receit)       ";
    $sSql .= "                           and not exists(select 1";
    $sSql .= "                             from abatimentoutilizacaodestino";
    $sSql .= "                               inner join abatimentoutilizacao on k157_sequencial = k170_utilizacao";
    $sSql .= "                               inner join abatimento on k125_sequencial = k157_abatimento";
    $sSql .= "                             where arrecant_pago.k00_numpre = k170_numpre";
    $sSql .= "                               and arrecant_pago.k00_receit = k170_receit";
    $sSql .= "                               and k125_tipoabatimento = " . Abatimento::TIPO_CREDITO;
    $sSql .= "                            limit 1)";
    $sSql .= "       union all ";
    $sSql .= "      select sum(abatimentoarreckey.k128_valorabatido) as valor ";
    $sSql .= "        from arrecad ";
    $sSql .= "             inner join arreckey on arrecad.k00_numpre = arreckey.k00_numpre ";
    $sSql .= "                                and arrecad.k00_numpar = arreckey.k00_numpar ";
    $sSql .= "                                and arrecad.k00_receit = arreckey.k00_receit ";
    $sSql .= "             inner join abatimentoarreckey on abatimentoarreckey.k128_arreckey = arreckey.k00_sequencial ";
    $sSql .= "             inner join abatimento on abatimentoarreckey.k128_abatimento = abatimento.k125_sequencial ";
    $sSql .= "       where arrecad.k00_numpre = vistorianumpre.y69_numpre ";
    $sSql .= "         and abatimento.k125_tipoabatimento = ".Abatimento::TIPO_PAGAMENTO_PARCIAL;
    $sSql .= "       union all ";
    $sSql .= "      select sum(abatimentoarreckey.k128_valorabatido) as valor ";
    $sSql .= "        from arrecant ";
    $sSql .= "             inner join arreckey on arrecant.k00_numpre = arreckey.k00_numpre ";
    $sSql .= "                                and arrecant.k00_numpar = arreckey.k00_numpar ";
    $sSql .= "                                and arrecant.k00_receit = arreckey.k00_receit ";
    $sSql .= "             inner join abatimentoarreckey on abatimentoarreckey.k128_arreckey = arreckey.k00_sequencial ";
    $sSql .= "             inner join abatimento on abatimentoarreckey.k128_abatimento = abatimento.k125_sequencial ";
    $sSql .= "       where arrecant.k00_numpre = vistorianumpre.y69_numpre ";
    $sSql .= "         and abatimento.k125_tipoabatimento = ".Abatimento::TIPO_PAGAMENTO_PARCIAL;
    $sSql .= "         ) as f ";
    $sSql .= "                       ) as valor_pago,                                                                                                    ";
    $sSql .= "                                                                                                                                           ";
    $sSql .= "                       ( select sum(arrecant_cancelamento.k00_valor)                                                                       ";
    $sSql .= "                           from arrecant as arrecant_cancelamento                                                                          ";
    $sSql .= "                                inner join cancdebitosreg      on cancdebitosreg.k21_numpre             = arrecant_cancelamento.k00_numpre ";
    $sSql .= "                                                              and cancdebitosreg.k21_numpar             = arrecant_cancelamento.k00_numpar ";
    $sSql .= "                                inner join cancdebitosprocreg  on cancdebitosprocreg.k24_cancdebitosreg = cancdebitosreg.k21_sequencia     ";
    $sSql .= "                          where arrecant_cancelamento.k00_numpre = vistorianumpre.y69_numpre                                               ";
    $sSql .= "                       ) as valor_cancelado,                                                                                               ";
    $sSql .= "                       ( select sum(k00_valor)                                                                                             ";
    $sSql .= "                           from arreold                                                                                                    ";
    $sSql .= "                          where k00_numpre in (select distinct k10_numpre                                                                  ";
    $sSql .= "                                                 from divold                                                                               ";
    $sSql .= "                                                where k10_numpre = vistorianumpre.y69_numpre)                                              ";
    $sSql .= "                       ) as valor_importado,                                                                                               ";
    $sSql .= "                       case                                                                                                                ";
    $sSql .= "                         when arrecad.k00_receit  is not null then arrecad.k00_receit                                                      ";
    $sSql .= "                         when arrepaga.k00_receit is not null then arrepaga.k00_receit                                                     ";
    $sSql .= "                         when arrecant.k00_receit is not null then arrecant.k00_receit                                                     ";
    $sSql .= "                       end as codigo_receita,                                                                                              ";
    $sSql .= "                       case                                                                                                                ";
    $sSql .= "                         when arrecad.k00_receit  is not null then arrecad.k00_tipo                                                        ";
    $sSql .= "                         when arrepaga.k00_receit is not null then arrecant.k00_tipo                                                       ";
    $sSql .= "                         when arrecant.k00_receit is not null then arrecant.k00_tipo                                                       ";
    $sSql .= "                       end as codigo_tipodebito,                                                                                           ";
    $sSql .= "                       case when vistinscr.y71_inscr is not null then 1 else 0 end as quantidade                                           ";
    $sSql .= "                                                                                                                                           ";
    $sSql .= "                  from vistorias                                                                                                           ";
    $sSql .= "                 inner join vistorianumpre on vistorianumpre.y69_codvist = vistorias.y70_codvist                                           ";
    $sSql .= "                 inner join vistinscr      on vistinscr.y71_codvist     = vistorias.y70_codvist                                            ";
    $sSql .= "                 left  join arrecad        on arrecad.k00_numpre         = vistorianumpre.y69_numpre                                       ";
    $sSql .= "                 left  join arrepaga       on arrepaga.k00_numpre        = vistorianumpre.y69_numpre                                       ";
    $sSql .= "                 left  join arrecant       on arrecant.k00_numpre        = vistorianumpre.y69_numpre                                       ";
    $sSql .= "                 where extract(year from y70_data) = {$iAnoCalculo}) as vistorias                                                          ";
    $sSql .= "         inner join tabrec   on tabrec.k02_codigo = codigo_receita                                                                         ";
    $sSql .= "         inner join arretipo on arretipo.k00_tipo = codigo_tipodebito                                                                      ";
    $sSql .= "         where (valor_a_pagar <> 0 or valor_pago <> 0 or valor_cancelado <> 0 or valor_compensado <> 0)                                 ";
    $sSql .= "         group by codigo_receita, codigo_tipodebito, k02_descr, k00_descr) as x                                                            ";
    $sSql .= "group by tipodebito,                                                                                                                       ";
    $sSql .= "         codigo_receita,                                                                                                                   ";
    $sSql .= "         receita, codigo_tipodebito                                                                                                        ";
    $sSql .= "order by tipodebito                                                                                                                        ";


    return $sSql;
  }
}
?>
