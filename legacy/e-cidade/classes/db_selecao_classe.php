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

//MODULO: pessoal
//CLASSE DA ENTIDADE selecao
class cl_selecao {
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
   var $r44_selec = 0;
   var $r44_instit = 0;
   var $r44_desc1 = null;
   var $r44_desc2 = null;
   var $r44_descr = null;
   var $r44_obs = null;
   var $r44_where = null;
   var $r44_gruposelecao = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 r44_selec = int4 = Seleção
                 r44_instit = int4 = Cod. Instituição
                 r44_desc1 = char(200) = descricao 1
                 r44_desc2 = char(200) = descricao 2
                 r44_descr = char(30) = Descrição
                 r44_obs = char(50) = Observacoes
                 r44_where = varchar(200) = Condição
                 r44_gruposelecao = int4 = Cod. Grupo
                 ";
   //funcao construtor da classe
   function cl_selecao() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("selecao");
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
       $this->r44_selec = ($this->r44_selec == ""?@$GLOBALS["HTTP_POST_VARS"]["r44_selec"]:$this->r44_selec);
       $this->r44_instit = ($this->r44_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["r44_instit"]:$this->r44_instit);
       $this->r44_desc1 = ($this->r44_desc1 == ""?@$GLOBALS["HTTP_POST_VARS"]["r44_desc1"]:$this->r44_desc1);
       $this->r44_desc2 = ($this->r44_desc2 == ""?@$GLOBALS["HTTP_POST_VARS"]["r44_desc2"]:$this->r44_desc2);
       $this->r44_descr = ($this->r44_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["r44_descr"]:$this->r44_descr);
       $this->r44_obs = ($this->r44_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["r44_obs"]:$this->r44_obs);
       $this->r44_where = ($this->r44_where == ""?@$GLOBALS["HTTP_POST_VARS"]["r44_where"]:$this->r44_where);
       $this->r44_gruposelecao = ($this->r44_gruposelecao == ""?@$GLOBALS["HTTP_POST_VARS"]["r44_gruposelecao"]:$this->r44_gruposelecao);
     }else{
       $this->r44_selec = ($this->r44_selec == ""?@$GLOBALS["HTTP_POST_VARS"]["r44_selec"]:$this->r44_selec);
       $this->r44_instit = ($this->r44_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["r44_instit"]:$this->r44_instit);
     }
   }
   // funcao para inclusao
   function incluir ($r44_selec,$r44_instit){
      $this->atualizacampos();
     if($this->r44_descr == null ){
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "r44_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r44_gruposelecao == null ){
       $this->erro_sql = " Campo Cod. Grupo nao Informado.";
       $this->erro_campo = "r44_gruposelecao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->r44_selec = $r44_selec;
       $this->r44_instit = $r44_instit;
     if(($this->r44_selec == null) || ($this->r44_selec == "") ){
       $this->erro_sql = " Campo r44_selec nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r44_instit == null) || ($this->r44_instit == "") ){
       $this->erro_sql = " Campo r44_instit nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into selecao(
                                       r44_selec
                                      ,r44_instit
                                      ,r44_desc1
                                      ,r44_desc2
                                      ,r44_descr
                                      ,r44_obs
                                      ,r44_where
                                      ,r44_gruposelecao
                       )
                values (
                                $this->r44_selec
                               ,$this->r44_instit
                               ,'$this->r44_desc1'
                               ,'$this->r44_desc2'
                               ,'$this->r44_descr'
                               ,'$this->r44_obs'
                               ,'$this->r44_where'
                               ,$this->r44_gruposelecao
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Selecoes ($this->r44_selec."-".$this->r44_instit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Selecoes já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Selecoes ($this->r44_selec."-".$this->r44_instit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r44_selec."-".$this->r44_instit;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->r44_selec,$this->r44_instit  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4473,'$this->r44_selec','I')");
         $resac = db_query("insert into db_acountkey values($acount,9911,'$this->r44_instit','I')");
         $resac = db_query("insert into db_acount values($acount,591,4473,'','".AddSlashes(pg_result($resaco,0,'r44_selec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,591,9911,'','".AddSlashes(pg_result($resaco,0,'r44_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,591,12367,'','".AddSlashes(pg_result($resaco,0,'r44_desc1'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,591,4475,'','".AddSlashes(pg_result($resaco,0,'r44_desc2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,591,4476,'','".AddSlashes(pg_result($resaco,0,'r44_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,591,4477,'','".AddSlashes(pg_result($resaco,0,'r44_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,591,4474,'','".AddSlashes(pg_result($resaco,0,'r44_where'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,591,20128,'','".AddSlashes(pg_result($resaco,0,'r44_gruposelecao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($r44_selec=null,$r44_instit=null) {
      $this->atualizacampos();
     $sql = " update selecao set ";
     $virgula = "";
     if(trim($this->r44_selec)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r44_selec"])){
       $sql  .= $virgula." r44_selec = $this->r44_selec ";
       $virgula = ",";
       if(trim($this->r44_selec) == null ){
         $this->erro_sql = " Campo Seleção nao Informado.";
         $this->erro_campo = "r44_selec";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r44_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r44_instit"])){
       $sql  .= $virgula." r44_instit = $this->r44_instit ";
       $virgula = ",";
       if(trim($this->r44_instit) == null ){
         $this->erro_sql = " Campo Cod. Instituição nao Informado.";
         $this->erro_campo = "r44_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r44_desc1)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r44_desc1"])){
       $sql  .= $virgula." r44_desc1 = '$this->r44_desc1' ";
       $virgula = ",";
     }
     if(trim($this->r44_desc2)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r44_desc2"])){
       $sql  .= $virgula." r44_desc2 = '$this->r44_desc2' ";
       $virgula = ",";
     }
     if(trim($this->r44_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r44_descr"])){
       $sql  .= $virgula." r44_descr = '$this->r44_descr' ";
       $virgula = ",";
       if(trim($this->r44_descr) == null ){
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "r44_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r44_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r44_obs"])){
       $sql  .= $virgula." r44_obs = '$this->r44_obs' ";
       $virgula = ",";
     }
     if(trim($this->r44_where)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r44_where"])){
       $sql  .= $virgula." r44_where = '$this->r44_where' ";
       $virgula = ",";
     }
     if(trim($this->r44_gruposelecao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r44_gruposelecao"])){
       $sql  .= $virgula." r44_gruposelecao = $this->r44_gruposelecao ";
       $virgula = ",";
       if(trim($this->r44_gruposelecao) == null ){
         $this->erro_sql = " Campo Cod. Grupo nao Informado.";
         $this->erro_campo = "r44_gruposelecao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($r44_selec!=null){
       $sql .= " r44_selec = $this->r44_selec";
     }
     if($r44_instit!=null){
       $sql .= " and  r44_instit = $this->r44_instit";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->r44_selec,$this->r44_instit));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,4473,'$this->r44_selec','A')");
           $resac = db_query("insert into db_acountkey values($acount,9911,'$this->r44_instit','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["r44_selec"]) || $this->r44_selec != "")
             $resac = db_query("insert into db_acount values($acount,591,4473,'".AddSlashes(pg_result($resaco,$conresaco,'r44_selec'))."','$this->r44_selec',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["r44_instit"]) || $this->r44_instit != "")
             $resac = db_query("insert into db_acount values($acount,591,9911,'".AddSlashes(pg_result($resaco,$conresaco,'r44_instit'))."','$this->r44_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["r44_desc1"]) || $this->r44_desc1 != "")
             $resac = db_query("insert into db_acount values($acount,591,12367,'".AddSlashes(pg_result($resaco,$conresaco,'r44_desc1'))."','$this->r44_desc1',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["r44_desc2"]) || $this->r44_desc2 != "")
             $resac = db_query("insert into db_acount values($acount,591,4475,'".AddSlashes(pg_result($resaco,$conresaco,'r44_desc2'))."','$this->r44_desc2',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["r44_descr"]) || $this->r44_descr != "")
             $resac = db_query("insert into db_acount values($acount,591,4476,'".AddSlashes(pg_result($resaco,$conresaco,'r44_descr'))."','$this->r44_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["r44_obs"]) || $this->r44_obs != "")
             $resac = db_query("insert into db_acount values($acount,591,4477,'".AddSlashes(pg_result($resaco,$conresaco,'r44_obs'))."','$this->r44_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["r44_where"]) || $this->r44_where != "")
             $resac = db_query("insert into db_acount values($acount,591,4474,'".AddSlashes(pg_result($resaco,$conresaco,'r44_where'))."','$this->r44_where',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["r44_gruposelecao"]) || $this->r44_gruposelecao != "")
             $resac = db_query("insert into db_acount values($acount,591,20128,'".AddSlashes(pg_result($resaco,$conresaco,'r44_gruposelecao'))."','$this->r44_gruposelecao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Selecoes nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->r44_selec."-".$this->r44_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Selecoes nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->r44_selec."-".$this->r44_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r44_selec."-".$this->r44_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($r44_selec=null,$r44_instit=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($r44_selec,$r44_instit));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,4473,'$r44_selec','E')");
           $resac  = db_query("insert into db_acountkey values($acount,9911,'$r44_instit','E')");
           $resac  = db_query("insert into db_acount values($acount,591,4473,'','".AddSlashes(pg_result($resaco,$iresaco,'r44_selec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,591,9911,'','".AddSlashes(pg_result($resaco,$iresaco,'r44_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,591,12367,'','".AddSlashes(pg_result($resaco,$iresaco,'r44_desc1'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,591,4475,'','".AddSlashes(pg_result($resaco,$iresaco,'r44_desc2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,591,4476,'','".AddSlashes(pg_result($resaco,$iresaco,'r44_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,591,4477,'','".AddSlashes(pg_result($resaco,$iresaco,'r44_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,591,4474,'','".AddSlashes(pg_result($resaco,$iresaco,'r44_where'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,591,20128,'','".AddSlashes(pg_result($resaco,$iresaco,'r44_gruposelecao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from selecao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($r44_selec != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r44_selec = $r44_selec ";
        }
        if($r44_instit != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r44_instit = $r44_instit ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Selecoes nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$r44_selec."-".$r44_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Selecoes nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$r44_selec."-".$r44_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$r44_selec."-".$r44_instit;
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
        $this->erro_sql   = "Record Vazio na Tabela:selecao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $r44_selec=null,$r44_instit=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from selecao ";
     $sql .= "      inner join db_config  on  db_config.codigo = selecao.r44_instit";
     $sql .= "      inner join gruposelecao  on  gruposelecao.rh122_sequencial = selecao.r44_gruposelecao";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
     $sql2 = "";
     if($dbwhere==""){
       if($r44_selec!=null ){
         $sql2 .= " where selecao.r44_selec = $r44_selec ";
       }
       if($r44_instit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " selecao.r44_instit = $r44_instit ";
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
   function sql_query_file ( $r44_selec=null,$r44_instit=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from selecao ";
     $sql2 = "";
     if($dbwhere==""){
       if($r44_selec!=null ){
         $sql2 .= " where selecao.r44_selec = $r44_selec ";
       }
       if($r44_instit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " selecao.r44_instit = $r44_instit ";
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
   function getDescricaoSelecao( $iCodigoSelecao ) {

     if (is_null($iInstituicao)) {
       $iInstituicao = db_getsession('DB_instit');
     }

     $sSqlSelecao   = $this->sql_query_file($iCodigoSelecao, $iInstituicao);
     $rsSelecao     = $this->sql_record($sSqlSelecao);

     if ( !$rsSelecao ) {
       throw new DBException( $this->erro_msg );
     }

     return $sWhereSelecao = db_utils::fieldsMemory( $rsSelecao, 0 )->r44_descr;
   }
   function getCondicaoSelecao( $iCodigoSelecao, $iInstituicao=null ) {

     if (is_null($iInstituicao)) {
       $iInstituicao = db_getsession('DB_instit');
     }

     $sSqlSelecao   = $this->sql_query_file($iCodigoSelecao, $iInstituicao);
     $rsSelecao     = $this->sql_record($sSqlSelecao);

     if ( !$rsSelecao ) {
       throw new DBException( $this->erro_msg );
     }

     return $sWhereSelecao = db_utils::fieldsMemory( $rsSelecao, 0 )->r44_where;
   }
   function sql_query_servidores( $iAnoUsu, $iMesUsu, $iCodigoSelecao, $sCampos, $iInstituicao = null) {

    if ( empty($sCampos) ) {
    	$sCampos = "*";
    }

    if (empty($iInstituicao)) {
    	$iInstituicao = db_getsession('DB_instit');
    }

    $sWhereSelecao    = $this->getCondicaoSelecao( $iCodigoSelecao );
    $oDaoRHPessoalMov = db_utils::getDao("rhpessoalmov");
    $sSQLBase         = $oDaoRHPessoalMov->sql_query_baseServidores( $iMesUsu,
                                                                 		 $iAnoUsu,
                                                                 		 $iInstituicao,
                                                                 		 $sCampos,
                                                                 		 $sWhereSelecao);
    return $sSQLBase;

	}
}
?>
