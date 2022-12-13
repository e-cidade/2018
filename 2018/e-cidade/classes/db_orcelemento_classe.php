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

//MODULO: orcamento
//CLASSE DA ENTIDADE orcelemento
class cl_orcelemento {
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
   var $o56_codele = 0;
   var $o56_anousu = 0;
   var $o56_elemento = null;
   var $o56_descr = null;
   var $o56_finali = null;
   var $o56_orcado = 'f';
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 o56_codele = int4 = Código Elemento
                 o56_anousu = int4 = Exercício
                 o56_elemento = varchar(13) = Elemento
                 o56_descr = varchar(50) = Descrição
                 o56_finali = text = Finalidade
                 o56_orcado = bool = Orçado
                 ";
   //funcao construtor da classe
   function cl_orcelemento() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orcelemento");
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
       $this->o56_codele = ($this->o56_codele == ""?@$GLOBALS["HTTP_POST_VARS"]["o56_codele"]:$this->o56_codele);
       $this->o56_anousu = ($this->o56_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["o56_anousu"]:$this->o56_anousu);
       $this->o56_elemento = ($this->o56_elemento == ""?@$GLOBALS["HTTP_POST_VARS"]["o56_elemento"]:$this->o56_elemento);
       $this->o56_descr = ($this->o56_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["o56_descr"]:$this->o56_descr);
       $this->o56_finali = ($this->o56_finali == ""?@$GLOBALS["HTTP_POST_VARS"]["o56_finali"]:$this->o56_finali);
       $this->o56_orcado = ($this->o56_orcado == "f"?@$GLOBALS["HTTP_POST_VARS"]["o56_orcado"]:$this->o56_orcado);
     }else{
       $this->o56_codele = ($this->o56_codele == ""?@$GLOBALS["HTTP_POST_VARS"]["o56_codele"]:$this->o56_codele);
       $this->o56_anousu = ($this->o56_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["o56_anousu"]:$this->o56_anousu);
     }
   }
   // funcao para inclusao
   function incluir ($o56_codele,$o56_anousu){
      $this->atualizacampos();
     if($this->o56_elemento == null ){
       $this->erro_sql = " Campo Elemento nao Informado.";
       $this->erro_campo = "o56_elemento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o56_descr == null ){
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "o56_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o56_orcado == null ){
       $this->erro_sql = " Campo Orçado nao Informado.";
       $this->erro_campo = "o56_orcado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->o56_codele = $o56_codele;
       $this->o56_anousu = $o56_anousu;
     if(($this->o56_codele == null) || ($this->o56_codele == "") ){
       $this->erro_sql = " Campo o56_codele nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->o56_anousu == null) || ($this->o56_anousu == "") ){
       $this->erro_sql = " Campo o56_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orcelemento(
                                       o56_codele
                                      ,o56_anousu
                                      ,o56_elemento
                                      ,o56_descr
                                      ,o56_finali
                                      ,o56_orcado
                       )
                values (
                                $this->o56_codele
                               ,$this->o56_anousu
                               ,'$this->o56_elemento'
                               ,'$this->o56_descr'
                               ,'$this->o56_finali'
                               ,'$this->o56_orcado'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Elementos da Despesa ($this->o56_codele."-".$this->o56_anousu) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Elementos da Despesa já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Elementos da Despesa ($this->o56_codele."-".$this->o56_anousu) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o56_codele."-".$this->o56_anousu;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o56_codele,$this->o56_anousu));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5356,'$this->o56_codele','I')");
       $resac = db_query("insert into db_acountkey values($acount,8063,'$this->o56_anousu','I')");
       $resac = db_query("insert into db_acount values($acount,753,5356,'','".AddSlashes(pg_result($resaco,0,'o56_codele'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,753,8063,'','".AddSlashes(pg_result($resaco,0,'o56_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,753,5357,'','".AddSlashes(pg_result($resaco,0,'o56_elemento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,753,5358,'','".AddSlashes(pg_result($resaco,0,'o56_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,753,5359,'','".AddSlashes(pg_result($resaco,0,'o56_finali'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,753,5360,'','".AddSlashes(pg_result($resaco,0,'o56_orcado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($o56_codele=null,$o56_anousu=null) {
      $this->atualizacampos();
     $sql = " update orcelemento set ";
     $virgula = "";
     if(trim($this->o56_codele)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o56_codele"])){
       $sql  .= $virgula." o56_codele = $this->o56_codele ";
       $virgula = ",";
       if(trim($this->o56_codele) == null ){
         $this->erro_sql = " Campo Código Elemento nao Informado.";
         $this->erro_campo = "o56_codele";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o56_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o56_anousu"])){
       $sql  .= $virgula." o56_anousu = $this->o56_anousu ";
       $virgula = ",";
       if(trim($this->o56_anousu) == null ){
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "o56_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o56_elemento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o56_elemento"])){
       $sql  .= $virgula." o56_elemento = '$this->o56_elemento' ";
       $virgula = ",";
       if(trim($this->o56_elemento) == null ){
         $this->erro_sql = " Campo Elemento nao Informado.";
         $this->erro_campo = "o56_elemento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o56_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o56_descr"])){
       $sql  .= $virgula." o56_descr = '$this->o56_descr' ";
       $virgula = ",";
       if(trim($this->o56_descr) == null ){
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "o56_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o56_finali)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o56_finali"])){
       $sql  .= $virgula." o56_finali = '$this->o56_finali' ";
       $virgula = ",";
     }
     if(trim($this->o56_orcado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o56_orcado"])){
       $sql  .= $virgula." o56_orcado = '$this->o56_orcado' ";
       $virgula = ",";
       if(trim($this->o56_orcado) == null ){
         $this->erro_sql = " Campo Orçado nao Informado.";
         $this->erro_campo = "o56_orcado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($o56_codele!=null){
       $sql .= " o56_codele = $this->o56_codele";
     }
     if($o56_anousu!=null){
       $sql .= " and  o56_anousu = $this->o56_anousu";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o56_codele,$this->o56_anousu));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5356,'$this->o56_codele','A')");
         $resac = db_query("insert into db_acountkey values($acount,8063,'$this->o56_anousu','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o56_codele"]))
           $resac = db_query("insert into db_acount values($acount,753,5356,'".AddSlashes(pg_result($resaco,$conresaco,'o56_codele'))."','$this->o56_codele',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o56_anousu"]))
           $resac = db_query("insert into db_acount values($acount,753,8063,'".AddSlashes(pg_result($resaco,$conresaco,'o56_anousu'))."','$this->o56_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o56_elemento"]))
           $resac = db_query("insert into db_acount values($acount,753,5357,'".AddSlashes(pg_result($resaco,$conresaco,'o56_elemento'))."','$this->o56_elemento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o56_descr"]))
           $resac = db_query("insert into db_acount values($acount,753,5358,'".AddSlashes(pg_result($resaco,$conresaco,'o56_descr'))."','$this->o56_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o56_finali"]))
           $resac = db_query("insert into db_acount values($acount,753,5359,'".AddSlashes(pg_result($resaco,$conresaco,'o56_finali'))."','$this->o56_finali',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o56_orcado"]))
           $resac = db_query("insert into db_acount values($acount,753,5360,'".AddSlashes(pg_result($resaco,$conresaco,'o56_orcado'))."','$this->o56_orcado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Elementos da Despesa nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o56_codele."-".$this->o56_anousu;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Elementos da Despesa nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o56_codele."-".$this->o56_anousu;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o56_codele."-".$this->o56_anousu;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($o56_codele=null,$o56_anousu=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o56_codele,$o56_anousu));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5356,'$o56_codele','E')");
         $resac = db_query("insert into db_acountkey values($acount,8063,'$o56_anousu','E')");
         $resac = db_query("insert into db_acount values($acount,753,5356,'','".AddSlashes(pg_result($resaco,$iresaco,'o56_codele'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,753,8063,'','".AddSlashes(pg_result($resaco,$iresaco,'o56_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,753,5357,'','".AddSlashes(pg_result($resaco,$iresaco,'o56_elemento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,753,5358,'','".AddSlashes(pg_result($resaco,$iresaco,'o56_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,753,5359,'','".AddSlashes(pg_result($resaco,$iresaco,'o56_finali'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,753,5360,'','".AddSlashes(pg_result($resaco,$iresaco,'o56_orcado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from orcelemento
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o56_codele != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o56_codele = $o56_codele ";
        }
        if($o56_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o56_anousu = $o56_anousu ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Elementos da Despesa nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o56_codele."-".$o56_anousu;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Elementos da Despesa nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o56_codele."-".$o56_anousu;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o56_codele."-".$o56_anousu;
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
        $this->erro_sql   = "Record Vazio na Tabela:orcelemento";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $o56_codele=null,$o56_anousu=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from orcelemento ";
     $sql2 = "";
     if($dbwhere==""){
       if($o56_codele!=null ){
         $sql2 .= " where orcelemento.o56_codele = $o56_codele ";
       }
       if($o56_anousu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " orcelemento.o56_anousu = $o56_anousu ";
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
   function sql_query_file ( $o56_codele=null,$o56_anousu=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from orcelemento ";
     $sql2 = "";
     if($dbwhere==""){
       if($o56_codele!=null ){
         $sql2 .= " where orcelemento.o56_codele = $o56_codele ";
       }
       if($o56_anousu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " orcelemento.o56_anousu = $o56_anousu ";
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
   function db_verifica_elemento($elemento){
    $nivel = db_le_mae($elemento,true);
    if($nivel == 1){
      return true;
    }
    $cod_mae = db_le_mae($elemento,false);
    $this->sql_record($this->sql_query_file("","o56_elemento",""," o56_anousu = ".db_getsession("DB_anousu")." and o56_elemento='$cod_mae'"));
    if($this->numrows<1){
      $this->erro_msg = 'Inclusão abortada. Elemento acima não encontrado!';
      return false;
    }
   if($nivel==9){
      return true;
    }
    if($nivel==8){
      $codigo = substr($elemento,0,9)."00";
      $where="substr(o56_elemento,1,11)='$codigo' and substr(o56_elemento,12,2)<>'00' ";
    }
    if($nivel==7){
      $codigo = substr($elemento,0,7)."00";
      $where="substr(o56_elemento,1,9)='$codigo' and substr(o56_elemento,10,4)<>'0000' ";
    }
    if($nivel==6){
      $codigo = substr($elemento,0,5)."00";
      $where="substr(o56_elemento,1,7)='$codigo' and substr(o56_elemento,8,6)<>'000000' ";
    }
    if($nivel==5){
         $codigo = substr($elemento,0,4)."0";
         $where="substr(o56_elemento,1,5)='$codigo' and substr(o56_elemento,6,8)<>'00000000' ";
    }
    if($nivel==4){
         $codigo = substr($elemento,0,3)."0";
         $where="substr(o56_elemento,1,4)='$codigo' and substr(o56_elemento,5,9)<>'000000000' ";
    }
    if($nivel==3){
         $codigo = substr($elemento,0,2)."0";
         $where="substr(o56_elemento,1,3)='$codigo' and substr(o56_elemento,4,10)<>'0000000000' ";
    }
    if($nivel==2){
         $codigo = substr($elemento,0,1)."0";
         $where="substr(o56_elemento,1,2)='$codigo' and substr(o56_elemento,3,11)<>'00000000000' ";
    }

    $where .= " and o56_anousu = ".db_getsession("DB_anousu");

    $result= $this->sql_record($this->sql_query_file("","","o56_elemento","",$where));
    if($this->numrows>0){
         $this->erro_msg = 'Inclusão abortada. Existe uma conta de nível inferior cadastrada!';
         return false;
    }
    $this->erro_msg = 'Elemento válido!';
    return true;
  }
   function db_verifica_elemento_exclusao($elemento,$o56_anousu=null){
   	 if ($o56_anousu==null){
   		 $o56_anousu = db_getsession("DB_anousu");
   	 }
     $nivel = db_le_mae($elemento,true);
     $cod_mae = db_le_mae($elemento,false);
     if($nivel==9){
          return true;
     }
     if($nivel==8){
         $codigo = substr($elemento,0,11);
         $where="substr(o56_elemento,1,11)='$codigo' and substr(o56_elemento,12,2)<>'00' ";
    }
    if($nivel==7){
         $codigo = substr($elemento,0,7);
         $where="substr(o56_elemento,1,9)='$codigo' and substr(o56_elemento,10,4)<>'0000' ";
    }
    if($nivel==6){
        $codigo = substr($elemento,0,7);
        $where="substr(o56_elemento,1,7)='$codigo' and substr(o56_elemento,8,6)<>'000000' ";
    }
    if($nivel==5){
        $codigo = substr($elemento,0,5);
        $where="substr(o56_elemento,1,5)='$codigo' and substr(o56_elemento,6,8)<>'00000000' ";
    }
    if($nivel==4){
        $codigo = substr($elemento,0,4);
        $where="substr(o56_elemento,1,4)='$codigo' and substr(o56_elemento,5,9)<>'000000000' ";
    }
    if($nivel==3){
        $codigo = substr($elemento,0,3);
        $where="substr(o56_elemento,1,3)='$codigo' and substr(o56_elemento,4,10)<>'0000000000' ";
    }
    if($nivel==2){
        $codigo = substr($elemento,0,2);
        $where="substr(o56_elemento,1,2)='$codigo' and substr(o56_elemento,3,11)<>'00000000000' ";
     }
     if($nivel==1){
        $codigo = substr($elemento,0,1);
        $where="substr(o56_elemento,1,1)='$codigo' and substr(o56_elemento,2,11)<>'00000000000' ";
     }
     $result= $this->sql_record($this->sql_query_file("",null,"o56_elemento","","  o56_anousu=$o56_anousu and ".$where));
     if($this->numrows>0){
        $this->erro_msg = 'Exclusão abortada. Existe uma conta de nível inferior cadastrada!';
        return false;
     }
     $this->erro_msg = 'Elemento com permissão de exclusão!';
      return true;
  }

   function sql_query_def ( $o56_codele=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from orcelemento ";
     $sql .= "    inner join rhrubelemento on rhrubelemento.rh23_codele = orcelemento.o56_codele ";
     $sql2 = "";
     if($dbwhere==""){
       if($o56_codele!=null ){
         $sql2 .= " where orcelemento.o56_anousu = ".db_getsession("DB_anousu")." and orcelemento.o56_codele = $o56_codele ";
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
   function sql_query_exercicio ( $anousu, $instits,$o56_codele=null,$campos="*",$ordem=null,$dbwhere=""){
  	 /*
  	  *  monta lista das instituições
  	  */
  	 $sp ="";
  	 $inst="";
  	 for ($x=0;$x < sizeof($instits);$x++){
        $inst .=  $sp.$instits[$x];
        $sp=",";
  	 }
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
     $sql .= " from orcelemento ";
     $sql .= "    inner join conplano on o56_codele = conplano.c60_codcon and o56_anousu=c60_anousu";
     $sql .= "    inner join conplanoreduz on c61_codcon = c60_codcon and c61_instit in (".$inst." ) and c61_anousu=".db_getsession("DB_anousu");

     $sql2 = "";
     if($dbwhere==""){
       if($o56_codele!=null ){
         $sql2 .= " where orcelemento.o56_anousu = ".db_getsession("DB_anousu")." and orcelemento.o56_codele = $o56_codele ";
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
     return analiseQueryPlanoOrcamento($sql);
  }

   function sql_query_nov ( $o56_codele=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from orcelemento ";
     $sql .= "    inner join rhelementoemp on rhelementoemp.rh38_codele = orcelemento.o56_codele ";
     $sql2 = "";
     if($dbwhere==""){
       if($o56_codele!=null ){
         $sql2 .= " where orcelemento.o56_anousu = ".db_getsession("DB_anousu")." and orcelemento.o56_codele = $o56_codele ";
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
     return analiseQueryPlanoOrcamento($sql);
  }
   function sql_query_pcmater ( $o56_codele=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from orcelemento ";
     $sql .= "    inner join pcmaterele on pc07_codele=o56_codele ";
     $sql .= "    inner join pcmater on pc01_codmater=pc07_codmater ";
     $sql2 = "";
     if($dbwhere==""){
       if($o56_codele!=null ){
         $sql2 .= " where orcelemento.o56_codele = $o56_codele ";
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
   function sql_query_razao($o56_codele=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from conlancamdot ";
     $sql .= "    inner join orcdotacao on o58_coddot=c73_coddot and o58_anousu=c73_anousu ";
     $sql .= "    inner join orcelemento on o56_codele = orcdotacao.o58_codele             ";
     $sql .= "                          and o56_anousu = orcdotacao.o58_anousu             ";


    // $sql .= " from orcelemento ";
    // $sql .= "   inner join orcdotacao on o56_codele = o58_codele ";
    // $sql .= "   inner join conlancamdot on c73_coddot = o58_coddot and c73_anousu = o58_anousu ";
    // $sql .= "   inner join conlancam on conlancam.c70_codlan = conlancamdot.c73_codlan and conlancam.c70_anousu=conlancam

     $sql2 = "";
     if($dbwhere==""){
       if($o56_codele!=null ){
         $sql2 .= " where orcelemento.o56_anousu = ".db_getsession("DB_anousu")." and orcelemento.o56_codele = $o56_codele ";
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

  function sql_query_conplano ($anousu, $instits,$campos="*",$ordem=null,$dbwhere=""){
  	 /*
  	  *  monta lista das instituições
  	  */
  	 $sp ="";
  	 $inst="";
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
     $sql .= " from orcelemento ";
     $sql .= "    inner join conplano on o56_codele = conplano.c60_codcon and o56_anousu=c60_anousu";
     $sql .= "    left join conplanoreduz on c61_codcon = c60_codcon  and c61_anousu=".db_getsession("DB_anousu");

     $sql2 = "";
     if(!empty($dbwhere)){
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
     return analiseQueryPlanoOrcamento($sql);
  }

  function sql_query_conplanoreduz ($anousu, $campos="*",$ordem=null,$dbwhere=""){
  	 /*
  	  *  monta lista das instituições
  	  */
  	 $sp ="";
  	 $inst="";
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
     $sql .= " from orcelemento ";
     $sql .= "    inner join conplano on o56_codele = conplano.c60_codcon and o56_anousu=c60_anousu";
     $sql .= "    left join conplanoreduz on c61_codcon = c60_codcon  and c61_anousu=".db_getsession("DB_anousu");
     $sql .= "                           and c61_instit=".db_getsession("DB_instit");

     $sql2 = "";
     if($dbwhere==""){
       if($o56_codele!=null ){
         $sql2 .= " where orcelemento.o56_anousu = ".db_getsession("DB_anousu")." and orcelemento.o56_codele = $o56_codele ";
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
     return analiseQueryPlanoOrcamento($sql);
  }

  function sql_query_contascomreduzido ($anousu, $campos="*",$ordem=null,$dbwhere=""){
    /*
     *  monta lista das instituições
    */
    $sp ="";
    $inst="";
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
    $sql .= " from orcelemento ";
    $sql .= "    inner join conplano on o56_codele = conplano.c60_codcon and o56_anousu=c60_anousu";
    $sql .= "    inner join conplanoreduz on c61_codcon = c60_codcon  and c61_anousu=".db_getsession("DB_anousu");
    $sql .= "                           and c61_instit=".db_getsession("DB_instit");

    $sql2 = "";
    if($dbwhere==""){
      if($o56_codele!=null ){
        $sql2 .= " where orcelemento.o56_anousu = ".db_getsession("DB_anousu")." and orcelemento.o56_codele = $o56_codele ";
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
      return analiseQueryPlanoOrcamento($sql);
  }

  function sql_query_desdobramento($o56_codele=null, $o56_anousu= null,$campos="*",$ordem=null,$dbwhere="") {
  	 /*
  	  *  monta lista das instituições
  	  */
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
     $sql .= " from orcelemento ";
     $sql .= "    inner join conplano on o56_codele = conplano.c60_codcon and o56_anousu=c60_anousu";
     $sql .= "    inner join conplanoreduz on c61_codcon = c60_codcon and c61_instit = ".DB_getsession("DB_instit");
     $sql .= "                                                        and c61_anousu = ".db_getsession("DB_anousu");
     if (!USE_PCASP) {
       $sql .= "    inner join conplanoexe on c61_reduz = c62_reduz     and c61_anousu = c62_anousu";
     }
     $sql2 = "";
     if($dbwhere==""){
       if($o56_codele!=null ){
         $sql2 .= " where orcelemento.o56_anousu = ".db_getsession("DB_anousu")." and orcelemento.o56_codele = $o56_codele ";
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
     return analiseQueryPlanoOrcamento($sql);
  }

  function sql_query_desdobramento_liberados($o56_codele=null, $o56_anousu= null,$campos="*",$ordem=null,$dbwhere="") {

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
     $sql .= " from orcelemento                                                                                       ";
     $sql .= "    inner join conplano                             on o56_codele  = conplano.c60_codcon                ";
     $sql .= "                                                   and o56_anousu  = c60_anousu                         ";
     $sql .= "    inner join conplanoreduz                        on c61_codcon  = c60_codcon                         ";
     $sql .= "                                                   and c61_instit  = ".DB_getsession("DB_instit");
     $sql .= "                                                   and c61_anousu  = ".db_getsession("DB_anousu");
     if (!USE_PCASP) {

       $sql .= "    inner join conplanoexe                          on c61_reduz   = c62_reduz                          ";
       $sql .= "                                                   and c61_anousu  = c62_anousu                         ";
     }
     $sql .= "    left  join desdobramentosliberadosordemcompra   on o56_codele  = pc33_codele                        ";
     $sql .= "                                                   and pc33_anousu = o56_anousu                         ";
     $sql2 = "";
     if($dbwhere==""){
       if($o56_codele!=null ){
         $sql2 .= " where orcelemento.o56_anousu = ".db_getsession("DB_anousu")." and orcelemento.o56_codele = $o56_codele ";
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
     return analiseQueryPlanoOrcamento($sql);
  }

  function sql_query_dotacao($o56_codele=null,$campos="*",$ordem=null,$dbwhere=""){

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
     $sql .= " from orcelemento ";
     $sql .= "    inner join orcdotacao  on o56_codele = orcdotacao.o58_codele             ";
     $sql .= "                          and o56_anousu = orcdotacao.o58_anousu             ";


     $sql2 = "";
     if($dbwhere==""){
       if($o56_codele!=null ){
         $sql2 .= " where orcelemento.o56_anousu = ".db_getsession("DB_anousu")." and orcelemento.o56_codele = $o56_codele ";
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


  function sql_query_plano_contas_execucao($anousu, $campos="*",$ordem=null,$dbwhere=""){

    $sql = "select ";
    if($campos != "*" ) {

      $campos_sql = split("#",$campos);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++) {

        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }

    $sql .= " from orcelemento ";
    $sql .= "    inner join empelemento on orcelemento.o56_codele = empelemento.e64_codele ";
    $sql .= "    inner join empempenho on empelemento.e64_numemp = empempenho.e60_numemp and orcelemento.o56_anousu = empempenho.e60_anousu ";
    $sql .= "    inner join conplano on o56_codele = conplano.c60_codcon and o56_anousu = c60_anousu ";
    $sql .= "    left join conplanoreduz on c60_anousu = c61_anousu and c60_codcon = c61_codcon and c61_instit = " . db_getsession("DB_instit");

    $sql2 = "";
    if($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if($ordem != null ) {

      $sql .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++) {

        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return analiseQueryPlanoOrcamento($sql);
  }
  function sql_query_plano_contas_dotacao($anousu, $campos="*",$ordem=null,$dbwhere=""){

    $sql = "select ";
    if($campos != "*" ) {

      $campos_sql = split("#",$campos);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++) {

        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }

    $sql .= " from orcelemento ";
    $sql .= "    inner join orcdotacao on orcdotacao.o58_codele = orcelemento.o56_codele and orcdotacao.o58_anousu = orcelemento.o56_anousu ";
    $sql .= "    inner join conplano on o56_codele = conplano.c60_codcon and o56_anousu = c60_anousu ";
    $sql .= "    left join conplanoreduz on c60_anousu = c61_anousu and c60_codcon = c61_codcon and c61_instit = " . db_getsession("DB_instit");

    $sql2 = "";
    if($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if($ordem != null ) {

      $sql .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++) {

        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return analiseQueryPlanoOrcamento($sql);
  }

  /**
   * @param string $campos
   * @param null $ordem
   * @param string $dbwhere
   * @return string
   */
  function sql_query_despesa_orcamento($campos="*",$ordem=null,$dbwhere=""){

    $sql  = "select ";
    $sql .= $campos;
    $sql .= " from orcelemento ";
    $sql .= "    inner join conplanoorcamento          on o56_codele = c60_codcon and o56_anousu = c60_anousu ";
    $sql .= "    inner join conplanoorcamentoanalitica on c60_anousu = c61_anousu and c60_codcon = c61_codcon ";

    if(!empty($dbwhere)) {
      $sql .= " where $dbwhere ";
    }

    if (!empty($ordem)) {
      $sql .= " order by {$ordem} ";
    }

    return $sql;
  }
}