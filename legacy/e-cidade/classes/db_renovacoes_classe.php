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

//MODULO: Cemiterio
//CLASSE DA ENTIDADE renovacoes
class cl_renovacoes {
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
   var $cm07_i_codigo = 0;
   var $cm07_i_sepultamento = 0;
   var $cm07_i_renovante = 0;
   var $cm07_c_motivo = null;
   var $cm07_d_ultima_dia = null;
   var $cm07_d_ultima_mes = null;
   var $cm07_d_ultima_ano = null;
   var $cm07_d_ultima = null;
   var $cm07_d_vencimento_dia = null;
   var $cm07_d_vencimento_mes = null;
   var $cm07_d_vencimento_ano = null;
   var $cm07_d_vencimento = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 cm07_i_codigo = int4 = Código
                 cm07_i_sepultamento = int4 = Sepultamento
                 cm07_i_renovante = int4 = Renovante
                 cm07_c_motivo = char(40) = Motivo
                 cm07_d_ultima = date = Ultima Renovação
                 cm07_d_vencimento = date = Vencimento
                 ";
   //funcao construtor da classe
   function cl_renovacoes() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("renovacoes");
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
       $this->cm07_i_codigo = ($this->cm07_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["cm07_i_codigo"]:$this->cm07_i_codigo);
       $this->cm07_i_sepultamento = ($this->cm07_i_sepultamento == ""?@$GLOBALS["HTTP_POST_VARS"]["cm07_i_sepultamento"]:$this->cm07_i_sepultamento);
       $this->cm07_i_renovante = ($this->cm07_i_renovante == ""?@$GLOBALS["HTTP_POST_VARS"]["cm07_i_renovante"]:$this->cm07_i_renovante);
       $this->cm07_c_motivo = ($this->cm07_c_motivo == ""?@$GLOBALS["HTTP_POST_VARS"]["cm07_c_motivo"]:$this->cm07_c_motivo);
       if($this->cm07_d_ultima == ""){
         $this->cm07_d_ultima_dia = ($this->cm07_d_ultima_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["cm07_d_ultima_dia"]:$this->cm07_d_ultima_dia);
         $this->cm07_d_ultima_mes = ($this->cm07_d_ultima_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["cm07_d_ultima_mes"]:$this->cm07_d_ultima_mes);
         $this->cm07_d_ultima_ano = ($this->cm07_d_ultima_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["cm07_d_ultima_ano"]:$this->cm07_d_ultima_ano);
         if($this->cm07_d_ultima_dia != ""){
            $this->cm07_d_ultima = $this->cm07_d_ultima_ano."-".$this->cm07_d_ultima_mes."-".$this->cm07_d_ultima_dia;
         }
       }
       if($this->cm07_d_vencimento == ""){
         $this->cm07_d_vencimento_dia = ($this->cm07_d_vencimento_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["cm07_d_vencimento_dia"]:$this->cm07_d_vencimento_dia);
         $this->cm07_d_vencimento_mes = ($this->cm07_d_vencimento_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["cm07_d_vencimento_mes"]:$this->cm07_d_vencimento_mes);
         $this->cm07_d_vencimento_ano = ($this->cm07_d_vencimento_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["cm07_d_vencimento_ano"]:$this->cm07_d_vencimento_ano);
         if($this->cm07_d_vencimento_dia != ""){
            $this->cm07_d_vencimento = $this->cm07_d_vencimento_ano."-".$this->cm07_d_vencimento_mes."-".$this->cm07_d_vencimento_dia;
         }
       }
     }else{
       $this->cm07_i_codigo = ($this->cm07_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["cm07_i_codigo"]:$this->cm07_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($cm07_i_codigo){
      $this->atualizacampos();
     if($this->cm07_i_sepultamento == null ){
       $this->erro_sql = " Campo Sepultamento nao Informado.";
       $this->erro_campo = "cm07_i_sepultamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm07_i_renovante == null ){
       $this->erro_sql = " Campo Renovante nao Informado.";
       $this->erro_campo = "cm07_i_renovante";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm07_d_ultima == null ){
       $this->cm07_d_ultima = "null";
     }
     if($this->cm07_d_vencimento == null ){
       $this->erro_sql = " Campo Vencimento nao Informado.";
       $this->erro_campo = "cm07_d_vencimento_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($cm07_i_codigo == "" || $cm07_i_codigo == null ){
       $result = db_query("select nextval('renovacoes_cm07_i_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: renovacoes_cm07_i_codigo_seq do campo: cm07_i_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->cm07_i_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from renovacoes_cm07_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $cm07_i_codigo)){
         $this->erro_sql = " Campo cm07_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->cm07_i_codigo = $cm07_i_codigo;
       }
     }
     if(($this->cm07_i_codigo == null) || ($this->cm07_i_codigo == "") ){
       $this->erro_sql = " Campo cm07_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into renovacoes(
                                       cm07_i_codigo
                                      ,cm07_i_sepultamento
                                      ,cm07_i_renovante
                                      ,cm07_c_motivo
                                      ,cm07_d_ultima
                                      ,cm07_d_vencimento
                       )
                values (
                                $this->cm07_i_codigo
                               ,$this->cm07_i_sepultamento
                               ,$this->cm07_i_renovante
                               ,'$this->cm07_c_motivo'
                               ,".($this->cm07_d_ultima == "null" || $this->cm07_d_ultima == ""?"null":"'".$this->cm07_d_ultima."'")."
                               ,".($this->cm07_d_vencimento == "null" || $this->cm07_d_vencimento == ""?"null":"'".$this->cm07_d_vencimento."'")."
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Renovações ($this->cm07_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Renovações já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Renovações ($this->cm07_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cm07_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->cm07_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10405,'$this->cm07_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1798,10405,'','".AddSlashes(pg_result($resaco,0,'cm07_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1798,10406,'','".AddSlashes(pg_result($resaco,0,'cm07_i_sepultamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1798,10407,'','".AddSlashes(pg_result($resaco,0,'cm07_i_renovante'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1798,10408,'','".AddSlashes(pg_result($resaco,0,'cm07_c_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1798,10409,'','".AddSlashes(pg_result($resaco,0,'cm07_d_ultima'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1798,10410,'','".AddSlashes(pg_result($resaco,0,'cm07_d_vencimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($cm07_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update renovacoes set ";
     $virgula = "";
     if(trim($this->cm07_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm07_i_codigo"])){
       $sql  .= $virgula." cm07_i_codigo = $this->cm07_i_codigo ";
       $virgula = ",";
       if(trim($this->cm07_i_codigo) == null ){
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "cm07_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm07_i_sepultamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm07_i_sepultamento"])){
       $sql  .= $virgula." cm07_i_sepultamento = $this->cm07_i_sepultamento ";
       $virgula = ",";
       if(trim($this->cm07_i_sepultamento) == null ){
         $this->erro_sql = " Campo Sepultamento nao Informado.";
         $this->erro_campo = "cm07_i_sepultamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm07_i_renovante)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm07_i_renovante"])){
       $sql  .= $virgula." cm07_i_renovante = $this->cm07_i_renovante ";
       $virgula = ",";
       if(trim($this->cm07_i_renovante) == null ){
         $this->erro_sql = " Campo Renovante nao Informado.";
         $this->erro_campo = "cm07_i_renovante";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm07_c_motivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm07_c_motivo"])){
       $sql  .= $virgula." cm07_c_motivo = '$this->cm07_c_motivo' ";
       $virgula = ",";
     }
     if(trim($this->cm07_d_ultima)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm07_d_ultima_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["cm07_d_ultima_dia"] !="") ){
       $sql  .= $virgula." cm07_d_ultima = '$this->cm07_d_ultima' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["cm07_d_ultima_dia"])){
         $sql  .= $virgula." cm07_d_ultima = null ";
         $virgula = ",";
       }
     }
     if(trim($this->cm07_d_vencimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm07_d_vencimento_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["cm07_d_vencimento_dia"] !="") ){
       $sql  .= $virgula." cm07_d_vencimento = '$this->cm07_d_vencimento' ";
       $virgula = ",";
       if(trim($this->cm07_d_vencimento) == null ){
         $this->erro_sql = " Campo Vencimento nao Informado.";
         $this->erro_campo = "cm07_d_vencimento_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["cm07_d_vencimento_dia"])){
         $sql  .= $virgula." cm07_d_vencimento = null ";
         $virgula = ",";
         if(trim($this->cm07_d_vencimento) == null ){
           $this->erro_sql = " Campo Vencimento nao Informado.";
           $this->erro_campo = "cm07_d_vencimento_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if($this->cm07_d_vencimento <= $this->cm07_d_ultima){
       $this->erro_sql = " Campo Vencimento menor ou Igual ao Ultima Renovação.";
       $this->erro_campo = "cm07_d_vencimento_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql .= " where ";
     if($cm07_i_codigo!=null){
       $sql .= " cm07_i_codigo = $this->cm07_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->cm07_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10405,'$this->cm07_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm07_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1798,10405,'".AddSlashes(pg_result($resaco,$conresaco,'cm07_i_codigo'))."','$this->cm07_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm07_i_sepultamento"]))
           $resac = db_query("insert into db_acount values($acount,1798,10406,'".AddSlashes(pg_result($resaco,$conresaco,'cm07_i_sepultamento'))."','$this->cm07_i_sepultamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm07_i_renovante"]))
           $resac = db_query("insert into db_acount values($acount,1798,10407,'".AddSlashes(pg_result($resaco,$conresaco,'cm07_i_renovante'))."','$this->cm07_i_renovante',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm07_c_motivo"]))
           $resac = db_query("insert into db_acount values($acount,1798,10408,'".AddSlashes(pg_result($resaco,$conresaco,'cm07_c_motivo'))."','$this->cm07_c_motivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm07_d_ultima"]))
           $resac = db_query("insert into db_acount values($acount,1798,10409,'".AddSlashes(pg_result($resaco,$conresaco,'cm07_d_ultima'))."','$this->cm07_d_ultima',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm07_d_vencimento"]))
           $resac = db_query("insert into db_acount values($acount,1798,10410,'".AddSlashes(pg_result($resaco,$conresaco,'cm07_d_vencimento'))."','$this->cm07_d_vencimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Renovações nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->cm07_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Renovações nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->cm07_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cm07_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($cm07_i_codigo=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($cm07_i_codigo));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10405,'$cm07_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1798,10405,'','".AddSlashes(pg_result($resaco,$iresaco,'cm07_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1798,10406,'','".AddSlashes(pg_result($resaco,$iresaco,'cm07_i_sepultamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1798,10407,'','".AddSlashes(pg_result($resaco,$iresaco,'cm07_i_renovante'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1798,10408,'','".AddSlashes(pg_result($resaco,$iresaco,'cm07_c_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1798,10409,'','".AddSlashes(pg_result($resaco,$iresaco,'cm07_d_ultima'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1798,10410,'','".AddSlashes(pg_result($resaco,$iresaco,'cm07_d_vencimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from renovacoes
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($cm07_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " cm07_i_codigo = $cm07_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Renovações nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$cm07_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Renovações nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$cm07_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$cm07_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:renovacoes";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $cm07_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from renovacoes ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = renovacoes.cm07_i_renovante";
     $sql .= "      inner join sepultamentos  on  sepultamentos.cm01_i_codigo = renovacoes.cm07_i_sepultamento";
     $sql .= "      inner join cgm as cgm1 on  cgm1.z01_numcgm = sepultamentos.cm01_i_codigo";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = sepultamentos.cm01_i_funcionario";
     $sql .= "      left  join legista  on  legista.cm32_i_codigo = sepultamentos.cm01_i_medico";
     $sql .= "      inner join causa  on  causa.cm04_i_codigo = sepultamentos.cm01_i_causa";
     $sql .= "      inner join cemiterio  on  cemiterio.cm14_i_codigo = sepultamentos.cm01_i_cemiterio";
     $sql .= "      left  join funerarias  on  funerarias.cm17_i_funeraria = sepultamentos.cm01_i_funeraria";
     $sql .= "      left  join hospitais  on  hospitais.cm18_i_hospital = sepultamentos.cm01_i_hospital";
     $sql2 = "";
     if($dbwhere==""){
       if($cm07_i_codigo!=null ){
         $sql2 .= " where renovacoes.cm07_i_codigo = $cm07_i_codigo ";
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
   function sql_query_file ( $cm07_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from renovacoes ";
     $sql2 = "";
     if($dbwhere==""){
       if($cm07_i_codigo!=null ){
         $sql2 .= " where renovacoes.cm07_i_codigo = $cm07_i_codigo ";
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
