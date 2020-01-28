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

//MODULO: Acordos
//CLASSE DA ENTIDADE acordoitemvinculo
class cl_acordoitemvinculo {
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
   var $ac33_sequencial = 0;
   var $ac33_acordoitempai = 0;
   var $ac33_acordoitemfilho = 0;
   var $ac33_tipo = 0;
   var $ac33_quantidade = 0;
   var $ac33_valorunitario = 0;
   var $ac33_valortotal = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 ac33_sequencial = int4 = Código Sequencial
                 ac33_acordoitempai = int4 = Item Original
                 ac33_acordoitemfilho = int4 = Item Derivado
                 ac33_tipo = int4 = Tipo do vinculo
                 ac33_quantidade = int4 = Quantidade
                 ac33_valorunitario = float8 = Valor unitario
                 ac33_valortotal = float8 = Valor total
                 ";
   //funcao construtor da classe
   function cl_acordoitemvinculo() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("acordoitemvinculo");
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
       $this->ac33_sequencial = ($this->ac33_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac33_sequencial"]:$this->ac33_sequencial);
       $this->ac33_acordoitempai = ($this->ac33_acordoitempai == ""?@$GLOBALS["HTTP_POST_VARS"]["ac33_acordoitempai"]:$this->ac33_acordoitempai);
       $this->ac33_acordoitemfilho = ($this->ac33_acordoitemfilho == ""?@$GLOBALS["HTTP_POST_VARS"]["ac33_acordoitemfilho"]:$this->ac33_acordoitemfilho);
       $this->ac33_tipo = ($this->ac33_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["ac33_tipo"]:$this->ac33_tipo);
       $this->ac33_quantidade = ($this->ac33_quantidade === ""?@$GLOBALS["HTTP_POST_VARS"]["ac33_quantidade"]:$this->ac33_quantidade);
       $this->ac33_valorunitario = ($this->ac33_valorunitario === ""?@$GLOBALS["HTTP_POST_VARS"]["ac33_valorunitario"]:$this->ac33_valorunitario);
       $this->ac33_valortotal = ($this->ac33_valortotal === ""?@$GLOBALS["HTTP_POST_VARS"]["ac33_valortotal"]:$this->ac33_valortotal);
     }else{
       $this->ac33_sequencial = ($this->ac33_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac33_sequencial"]:$this->ac33_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ac33_sequencial){
      $this->atualizacampos();
     if($this->ac33_acordoitempai == null ){
       $this->erro_sql = " Campo Item Original nao Informado.";
       $this->erro_campo = "ac33_acordoitempai";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac33_acordoitemfilho == null ){
       $this->erro_sql = " Campo Item Derivado nao Informado.";
       $this->erro_campo = "ac33_acordoitemfilho";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac33_tipo == null ){
       $this->erro_sql = " Campo Tipo do vinculo nao Informado.";
       $this->erro_campo = "ac33_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac33_quantidade === null ){
       $this->erro_sql = " Campo Quantidade nao Informado.";
       $this->erro_campo = "ac33_quantidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac33_valorunitario === null ){
       $this->erro_sql = " Campo Valor unitario nao Informado.";
       $this->erro_campo = "ac33_valorunitario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac33_valortotal === null ){
       $this->erro_sql = " Campo Valor total nao Informado.";
       $this->erro_campo = "ac33_valortotal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ac33_sequencial == "" || $ac33_sequencial == null ){
       $result = db_query("select nextval('acordoitemvinculo_ac33_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: acordoitemvinculo_ac33_sequencial_seq do campo: ac33_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->ac33_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from acordoitemvinculo_ac33_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ac33_sequencial)){
         $this->erro_sql = " Campo ac33_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ac33_sequencial = $ac33_sequencial;
       }
     }
     if(($this->ac33_sequencial == null) || ($this->ac33_sequencial == "") ){
       $this->erro_sql = " Campo ac33_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into acordoitemvinculo(
                                       ac33_sequencial
                                      ,ac33_acordoitempai
                                      ,ac33_acordoitemfilho
                                      ,ac33_tipo
                                      ,ac33_quantidade
                                      ,ac33_valorunitario
                                      ,ac33_valortotal
                       )
                values (
                                $this->ac33_sequencial
                               ,$this->ac33_acordoitempai
                               ,$this->ac33_acordoitemfilho
                               ,$this->ac33_tipo
                               ,$this->ac33_quantidade
                               ,$this->ac33_valorunitario
                               ,$this->ac33_valortotal
                      )";
     $result = db_query($sql);
     if($result==false){
       die(pg_last_error());
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "VInculos dos itens entre aditamentos ($this->ac33_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "VInculos dos itens entre aditamentos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "VInculos dos itens entre aditamentos ($this->ac33_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac33_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ac33_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17181,'$this->ac33_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3037,17181,'','".AddSlashes(pg_result($resaco,0,'ac33_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3037,17182,'','".AddSlashes(pg_result($resaco,0,'ac33_acordoitempai'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3037,17183,'','".AddSlashes(pg_result($resaco,0,'ac33_acordoitemfilho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3037,17184,'','".AddSlashes(pg_result($resaco,0,'ac33_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3037,17185,'','".AddSlashes(pg_result($resaco,0,'ac33_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3037,17186,'','".AddSlashes(pg_result($resaco,0,'ac33_valorunitario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3037,17187,'','".AddSlashes(pg_result($resaco,0,'ac33_valortotal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($ac33_sequencial=null) {
      $this->atualizacampos();
     $sql = " update acordoitemvinculo set ";
     $virgula = "";
     if(trim($this->ac33_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac33_sequencial"])){
       $sql  .= $virgula." ac33_sequencial = $this->ac33_sequencial ";
       $virgula = ",";
       if(trim($this->ac33_sequencial) == null ){
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "ac33_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac33_acordoitempai)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac33_acordoitempai"])){
       $sql  .= $virgula." ac33_acordoitempai = $this->ac33_acordoitempai ";
       $virgula = ",";
       if(trim($this->ac33_acordoitempai) == null ){
         $this->erro_sql = " Campo Item Original nao Informado.";
         $this->erro_campo = "ac33_acordoitempai";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac33_acordoitemfilho)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac33_acordoitemfilho"])){
       $sql  .= $virgula." ac33_acordoitemfilho = $this->ac33_acordoitemfilho ";
       $virgula = ",";
       if(trim($this->ac33_acordoitemfilho) == null ){
         $this->erro_sql = " Campo Item Derivado nao Informado.";
         $this->erro_campo = "ac33_acordoitemfilho";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac33_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac33_tipo"])){
       $sql  .= $virgula." ac33_tipo = $this->ac33_tipo ";
       $virgula = ",";
       if(trim($this->ac33_tipo) == null ){
         $this->erro_sql = " Campo Tipo do vinculo nao Informado.";
         $this->erro_campo = "ac33_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac33_quantidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac33_quantidade"])){
       $sql  .= $virgula." ac33_quantidade = $this->ac33_quantidade ";
       $virgula = ",";
       if(trim($this->ac33_quantidade) === null ){
         $this->erro_sql = " Campo Quantidade nao Informado.";
         $this->erro_campo = "ac33_quantidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac33_valorunitario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac33_valorunitario"])){
       $sql  .= $virgula." ac33_valorunitario = $this->ac33_valorunitario ";
       $virgula = ",";
       if(trim($this->ac33_valorunitario) === null ){
         $this->erro_sql = " Campo Valor unitario nao Informado.";
         $this->erro_campo = "ac33_valorunitario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac33_valortotal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac33_valortotal"])){
       $sql  .= $virgula." ac33_valortotal = $this->ac33_valortotal ";
       $virgula = ",";
       if(trim($this->ac33_valortotal) === null ){
         $this->erro_sql = " Campo Valor total nao Informado.";
         $this->erro_campo = "ac33_valortotal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ac33_sequencial!=null){
       $sql .= " ac33_sequencial = $this->ac33_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ac33_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17181,'$this->ac33_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac33_sequencial"]) || $this->ac33_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3037,17181,'".AddSlashes(pg_result($resaco,$conresaco,'ac33_sequencial'))."','$this->ac33_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac33_acordoitempai"]) || $this->ac33_acordoitempai != "")
           $resac = db_query("insert into db_acount values($acount,3037,17182,'".AddSlashes(pg_result($resaco,$conresaco,'ac33_acordoitempai'))."','$this->ac33_acordoitempai',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac33_acordoitemfilho"]) || $this->ac33_acordoitemfilho != "")
           $resac = db_query("insert into db_acount values($acount,3037,17183,'".AddSlashes(pg_result($resaco,$conresaco,'ac33_acordoitemfilho'))."','$this->ac33_acordoitemfilho',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac33_tipo"]) || $this->ac33_tipo != "")
           $resac = db_query("insert into db_acount values($acount,3037,17184,'".AddSlashes(pg_result($resaco,$conresaco,'ac33_tipo'))."','$this->ac33_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac33_quantidade"]) || $this->ac33_quantidade != "")
           $resac = db_query("insert into db_acount values($acount,3037,17185,'".AddSlashes(pg_result($resaco,$conresaco,'ac33_quantidade'))."','$this->ac33_quantidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac33_valorunitario"]) || $this->ac33_valorunitario != "")
           $resac = db_query("insert into db_acount values($acount,3037,17186,'".AddSlashes(pg_result($resaco,$conresaco,'ac33_valorunitario'))."','$this->ac33_valorunitario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac33_valortotal"]) || $this->ac33_valortotal != "")
           $resac = db_query("insert into db_acount values($acount,3037,17187,'".AddSlashes(pg_result($resaco,$conresaco,'ac33_valortotal'))."','$this->ac33_valortotal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "VInculos dos itens entre aditamentos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac33_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "VInculos dos itens entre aditamentos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac33_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac33_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($ac33_sequencial=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ac33_sequencial));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17181,'$ac33_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3037,17181,'','".AddSlashes(pg_result($resaco,$iresaco,'ac33_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3037,17182,'','".AddSlashes(pg_result($resaco,$iresaco,'ac33_acordoitempai'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3037,17183,'','".AddSlashes(pg_result($resaco,$iresaco,'ac33_acordoitemfilho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3037,17184,'','".AddSlashes(pg_result($resaco,$iresaco,'ac33_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3037,17185,'','".AddSlashes(pg_result($resaco,$iresaco,'ac33_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3037,17186,'','".AddSlashes(pg_result($resaco,$iresaco,'ac33_valorunitario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3037,17187,'','".AddSlashes(pg_result($resaco,$iresaco,'ac33_valortotal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from acordoitemvinculo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ac33_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ac33_sequencial = $ac33_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "VInculos dos itens entre aditamentos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ac33_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "VInculos dos itens entre aditamentos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ac33_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ac33_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:acordoitemvinculo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $ac33_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from acordoitemvinculo ";
     $sql .= "      inner join acordoitem  on  acordoitem.ac20_sequencial = acordoitemvinculo.ac33_acordoitempai";
     $sql .= "      inner join pcmater  on  pcmater.pc01_codmater = acordoitem.ac20_pcmater";
     $sql .= "      inner join matunid  on  matunid.m61_codmatunid = acordoitem.ac20_matunid";
     $sql .= "      inner join acordoposicao  on  acordoposicao.ac26_sequencial = acordoitem.ac20_acordoposicao";
     $sql2 = "";
     if($dbwhere==""){
       if($ac33_sequencial!=null ){
         $sql2 .= " where acordoitemvinculo.ac33_sequencial = $ac33_sequencial ";
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
   function sql_query_file ( $ac33_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from acordoitemvinculo ";
     $sql2 = "";
     if($dbwhere==""){
       if($ac33_sequencial!=null ){
         $sql2 .= " where acordoitemvinculo.ac33_sequencial = $ac33_sequencial ";
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