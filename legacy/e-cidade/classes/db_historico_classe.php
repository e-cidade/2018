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

//MODULO: educação
//CLASSE DA ENTIDADE historico
class cl_historico {
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
   var $ed61_i_codigo = 0;
   var $ed61_i_escola = 0;
   var $ed61_i_aluno = 0;
   var $ed61_i_curso = 0;
   var $ed61_t_obs = null;
   var $ed61_i_anoconc = 0;
   var $ed61_i_periodoconc = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 ed61_i_codigo = int8 = Código
                 ed61_i_escola = int8 = Escola
                 ed61_i_aluno = int8 = Aluno
                 ed61_i_curso = int8 = Curso
                 ed61_t_obs = text = Observações
                 ed61_i_anoconc = int4 = Ano Conclusão
                 ed61_i_periodoconc = int4 = Período Conclusão
                 ";
   //funcao construtor da classe
   function cl_historico() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("historico");
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
       $this->ed61_i_codigo = ($this->ed61_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed61_i_codigo"]:$this->ed61_i_codigo);
       $this->ed61_i_escola = ($this->ed61_i_escola == ""?@$GLOBALS["HTTP_POST_VARS"]["ed61_i_escola"]:$this->ed61_i_escola);
       $this->ed61_i_aluno = ($this->ed61_i_aluno == ""?@$GLOBALS["HTTP_POST_VARS"]["ed61_i_aluno"]:$this->ed61_i_aluno);
       $this->ed61_i_curso = ($this->ed61_i_curso == ""?@$GLOBALS["HTTP_POST_VARS"]["ed61_i_curso"]:$this->ed61_i_curso);
       $this->ed61_t_obs = ($this->ed61_t_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["ed61_t_obs"]:$this->ed61_t_obs);
       $this->ed61_i_anoconc = ($this->ed61_i_anoconc == ""?@$GLOBALS["HTTP_POST_VARS"]["ed61_i_anoconc"]:$this->ed61_i_anoconc);
       $this->ed61_i_periodoconc = ($this->ed61_i_periodoconc == ""?@$GLOBALS["HTTP_POST_VARS"]["ed61_i_periodoconc"]:$this->ed61_i_periodoconc);
     }else{
       $this->ed61_i_codigo = ($this->ed61_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed61_i_codigo"]:$this->ed61_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed61_i_codigo){
      $this->atualizacampos();
     if($this->ed61_i_escola == null ){
       $this->erro_sql = " Campo Escola nao Informado.";
       $this->erro_campo = "ed61_i_escola";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed61_i_aluno == null ){
       $this->erro_sql = " Campo Aluno nao Informado.";
       $this->erro_campo = "ed61_i_aluno";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed61_i_curso == null ){
       $this->erro_sql = " Campo Curso nao Informado.";
       $this->erro_campo = "ed61_i_curso";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed61_i_anoconc == null ){
       $this->ed61_i_anoconc = "null";
     }
     if($this->ed61_i_periodoconc == null ){
       $this->ed61_i_periodoconc = "null";
     }
     if($ed61_i_codigo == "" || $ed61_i_codigo == null ){
       $result = db_query("select nextval('historico_ed61_i_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: historico_ed61_i_codigo_seq do campo: ed61_i_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->ed61_i_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from historico_ed61_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed61_i_codigo)){
         $this->erro_sql = " Campo ed61_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed61_i_codigo = $ed61_i_codigo;
       }
     }
     if(($this->ed61_i_codigo == null) || ($this->ed61_i_codigo == "") ){
       $this->erro_sql = " Campo ed61_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into historico(
                                       ed61_i_codigo
                                      ,ed61_i_escola
                                      ,ed61_i_aluno
                                      ,ed61_i_curso
                                      ,ed61_t_obs
                                      ,ed61_i_anoconc
                                      ,ed61_i_periodoconc
                       )
                values (
                                $this->ed61_i_codigo
                               ,$this->ed61_i_escola
                               ,$this->ed61_i_aluno
                               ,$this->ed61_i_curso
                               ,'$this->ed61_t_obs'
                               ,$this->ed61_i_anoconc
                               ,$this->ed61_i_periodoconc
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Histórico do Aluno ($this->ed61_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Histórico do Aluno já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Histórico do Aluno ($this->ed61_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed61_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed61_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1008744,'$this->ed61_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1010129,1008744,'','".AddSlashes(pg_result($resaco,0,'ed61_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010129,1008745,'','".AddSlashes(pg_result($resaco,0,'ed61_i_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010129,1008746,'','".AddSlashes(pg_result($resaco,0,'ed61_i_aluno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010129,1008747,'','".AddSlashes(pg_result($resaco,0,'ed61_i_curso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010129,1008748,'','".AddSlashes(pg_result($resaco,0,'ed61_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010129,1008749,'','".AddSlashes(pg_result($resaco,0,'ed61_i_anoconc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010129,1008750,'','".AddSlashes(pg_result($resaco,0,'ed61_i_periodoconc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($ed61_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update historico set ";
     $virgula = "";
     if(trim($this->ed61_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed61_i_codigo"])){
       $sql  .= $virgula." ed61_i_codigo = $this->ed61_i_codigo ";
       $virgula = ",";
       if(trim($this->ed61_i_codigo) == null ){
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed61_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed61_i_escola)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed61_i_escola"])){
       $sql  .= $virgula." ed61_i_escola = $this->ed61_i_escola ";
       $virgula = ",";
       if(trim($this->ed61_i_escola) == null ){
         $this->erro_sql = " Campo Escola nao Informado.";
         $this->erro_campo = "ed61_i_escola";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed61_i_aluno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed61_i_aluno"])){
       $sql  .= $virgula." ed61_i_aluno = $this->ed61_i_aluno ";
       $virgula = ",";
       if(trim($this->ed61_i_aluno) == null ){
         $this->erro_sql = " Campo Aluno nao Informado.";
         $this->erro_campo = "ed61_i_aluno";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed61_i_curso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed61_i_curso"])){
       $sql  .= $virgula." ed61_i_curso = $this->ed61_i_curso ";
       $virgula = ",";
       if(trim($this->ed61_i_curso) == null ){
         $this->erro_sql = " Campo Curso nao Informado.";
         $this->erro_campo = "ed61_i_curso";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed61_t_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed61_t_obs"])){
       $sql  .= $virgula." ed61_t_obs = '$this->ed61_t_obs' ";
       $virgula = ",";
     }
     if(trim($this->ed61_i_anoconc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed61_i_anoconc"])){
        if(trim($this->ed61_i_anoconc)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed61_i_anoconc"])){
           $this->ed61_i_anoconc = "null" ;
        }
       $sql  .= $virgula." ed61_i_anoconc = $this->ed61_i_anoconc ";
       $virgula = ",";
     }
     if(trim($this->ed61_i_periodoconc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed61_i_periodoconc"])){
        if(trim($this->ed61_i_periodoconc)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed61_i_periodoconc"])){
           $this->ed61_i_periodoconc = "null" ;
        }
       $sql  .= $virgula." ed61_i_periodoconc = $this->ed61_i_periodoconc ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($ed61_i_codigo!=null){
       $sql .= " ed61_i_codigo = $this->ed61_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed61_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008744,'$this->ed61_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed61_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1010129,1008744,'".AddSlashes(pg_result($resaco,$conresaco,'ed61_i_codigo'))."','$this->ed61_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed61_i_escola"]))
           $resac = db_query("insert into db_acount values($acount,1010129,1008745,'".AddSlashes(pg_result($resaco,$conresaco,'ed61_i_escola'))."','$this->ed61_i_escola',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed61_i_aluno"]))
           $resac = db_query("insert into db_acount values($acount,1010129,1008746,'".AddSlashes(pg_result($resaco,$conresaco,'ed61_i_aluno'))."','$this->ed61_i_aluno',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed61_i_curso"]))
           $resac = db_query("insert into db_acount values($acount,1010129,1008747,'".AddSlashes(pg_result($resaco,$conresaco,'ed61_i_curso'))."','$this->ed61_i_curso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed61_t_obs"]))
           $resac = db_query("insert into db_acount values($acount,1010129,1008748,'".AddSlashes(pg_result($resaco,$conresaco,'ed61_t_obs'))."','$this->ed61_t_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed61_i_anoconc"]))
           $resac = db_query("insert into db_acount values($acount,1010129,1008749,'".AddSlashes(pg_result($resaco,$conresaco,'ed61_i_anoconc'))."','$this->ed61_i_anoconc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed61_i_periodoconc"]))
           $resac = db_query("insert into db_acount values($acount,1010129,1008750,'".AddSlashes(pg_result($resaco,$conresaco,'ed61_i_periodoconc'))."','$this->ed61_i_periodoconc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Histórico do Aluno nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed61_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Histórico do Aluno nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed61_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed61_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($ed61_i_codigo=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed61_i_codigo));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008744,'$ed61_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1010129,1008744,'','".AddSlashes(pg_result($resaco,$iresaco,'ed61_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010129,1008745,'','".AddSlashes(pg_result($resaco,$iresaco,'ed61_i_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010129,1008746,'','".AddSlashes(pg_result($resaco,$iresaco,'ed61_i_aluno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010129,1008747,'','".AddSlashes(pg_result($resaco,$iresaco,'ed61_i_curso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010129,1008748,'','".AddSlashes(pg_result($resaco,$iresaco,'ed61_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010129,1008749,'','".AddSlashes(pg_result($resaco,$iresaco,'ed61_i_anoconc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010129,1008750,'','".AddSlashes(pg_result($resaco,$iresaco,'ed61_i_periodoconc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from historico
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed61_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed61_i_codigo = $ed61_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Histórico do Aluno nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed61_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Histórico do Aluno nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed61_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed61_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:historico";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $ed61_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from historico ";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = historico.ed61_i_escola";
     $sql .= "      inner join cursoedu  on  cursoedu.ed29_i_codigo = historico.ed61_i_curso";
     $sql .= "      inner join aluno  on  aluno.ed47_i_codigo = historico.ed61_i_aluno";
     $sql .= "      inner join bairro  on  bairro.j13_codi = escola.ed18_i_bairro";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = escola.ed18_i_rua";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = escola.ed18_i_codigo";
     $sql .= "      inner join ensino  on  ensino.ed10_i_codigo = cursoedu.ed29_i_ensino";
     $sql2 = "";
     if($dbwhere==""){
       if($ed61_i_codigo!=null ){
         $sql2 .= " where historico.ed61_i_codigo = $ed61_i_codigo ";
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
   function sql_query_bol ( $ed61_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from historico ";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = historico.ed61_i_escola";
     $sql .= "      inner join cursoedu  on  cursoedu.ed29_i_codigo = historico.ed61_i_curso";
     $sql .= "      inner join aluno  on  aluno.ed47_i_codigo = historico.ed61_i_aluno";
     $sql .= "      inner join bairro  on  bairro.j13_codi = escola.ed18_i_bairro";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = escola.ed18_i_rua";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = escola.ed18_i_codigo";
     $sql .= "      left join ruascep on ruascep.j29_codigo = ruas.j14_codigo ";
     $sql .= "      left join logradcep on logradcep.j65_lograd = ruas.j14_codigo ";
     $sql .= "      left join ceplogradouros on ceplogradouros.cp06_codlogradouro = logradcep.j65_ceplog ";
     $sql .= "      left join ceplocalidades on ceplocalidades.cp05_codlocalidades = ceplogradouros.cp06_codlocalidade ";
     $sql .= "      inner join ensino  on  ensino.ed10_i_codigo = cursoedu.ed29_i_ensino";
     $sql2 = "";
     if($dbwhere==""){
       if($ed61_i_codigo!=null ){
         $sql2 .= " where historico.ed61_i_codigo = $ed61_i_codigo ";
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
   function sql_query_file ( $ed61_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from historico ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed61_i_codigo!=null ){
         $sql2 .= " where historico.ed61_i_codigo = $ed61_i_codigo ";
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

  function sql_query_union($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

    $sSql = 'select ';
    if ($sCampos != '*') {

      $sCamposSql = split('#', $sCampos);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++){

        $sSql .= $sVirgula.$sCamposSql[$iCont];
        $virgula = ",";

      }

    } else {
      $sSql .= $sCampos;
    }
    $sSql .= '  from ((select true as forarede, ed62_i_anoref as ano, ed62_c_resultadofinal as resfinal, ';
    $sSql .= '                serie.*, historico.* ';
    $sSql .= '             from historico ';
    $sSql .= '                  inner join historicomps on historicomps.ed62_i_historico = historico.ed61_i_codigo ';
    $sSql .= '               inner join serie on serie.ed11_i_codigo = historicomps.ed62_i_serie) ';
    $sSql .= '                 union ';
    $sSql .= '           (select false as forarede, ed99_i_anoref as ano, ed99_c_resultadofinal as resfinal, ';
    $sSql .= '                   serie.*, historico.* ';
    $sSql .= '              from historico inner join historicompsfora on ';
    $sSql .= '                historicompsfora.ed99_i_historico = historico.ed61_i_codigo ';
    $sSql .= '              inner join serie on serie.ed11_i_codigo = historicompsfora.ed99_i_serie)) as a ';
    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where historico.ed61_i_codigo = $iCodigo "; // Alterar aqui
      }

    } elseif ($sDbWhere != '') {
      $sSql2 = " where $sDbWhere";
    }
    $sSql .= $sSql2;

    if ($sOrdem != null) {

      $sSql      .= ' order by ';
      $sCamposSql = split('#', $sOrdem);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++) {

        $sSql    .= $sVirgula.$sCamposSql[$iCont];
        $sVirgula = ',';

      }

    }

    return $sSql;

  }

  function sql_query_relatorio($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

    $sSql = 'select ';
    if ($sCampos != '*') {

      $sCamposSql = split('#', $sCampos);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++){

        $sSql .= $sVirgula.$sCamposSql[$iCont];
        $virgula = ",";

      }

    } else {
      $sSql .= $sCampos;
    }
    $sSql .= " from historico ";
    $sSql .= "      inner join aluno on ed47_i_codigo = ed61_i_aluno ";
    $sSql .= "      inner join cursoedu on ed29_i_codigo = ed61_i_curso ";
    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where historico.ed61_i_codigo = $iCodigo ";
      }

    } elseif ($sDbWhere != '') {
      $sSql2 = " where $sDbWhere";
    }
    $sSql .= $sSql2;

    if ($sOrdem != null) {

      $sSql      .= ' order by ';
      $sCamposSql = split('#', $sOrdem);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++) {

        $sSql    .= $sVirgula.$sCamposSql[$iCont];
        $sVirgula = ',';

      }

    }

    return $sSql;

  }

    function sql_query_alunos_vinculados_escola($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

    $sSql = 'select ';
    if ($sCampos != '*') {

      $sCamposSql = split('#', $sCampos);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++){

        $sSql .= $sVirgula.$sCamposSql[$iCont];
        $virgula = ",";

      }

    } else {
      $sSql .= $sCampos;
    }
    $sSql .= " from historico ";
    $sSql .= "      inner join aluno on ed47_i_codigo = ed61_i_aluno ";
    $sSql .= "      inner join cursoedu on ed29_i_codigo = ed61_i_curso ";
    $sSql .= "      INNER JOIN alunocurso on ed56_i_aluno = ed47_i_codigo ";
    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where historico.ed61_i_codigo = $iCodigo ";
      }

    } elseif ($sDbWhere != '') {
      $sSql2 = " where $sDbWhere";
    }
    $sSql .= $sSql2;

    if ($sOrdem != null) {

      $sSql      .= ' order by ';
      $sCamposSql = split('#', $sOrdem);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++) {

        $sSql    .= $sVirgula.$sCamposSql[$iCont];
        $sVirgula = ',';

      }

    }

    return $sSql;

  }

  function sql_query_aluno($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

    $sSql = 'select ';
    if ($sCampos != '*') {

      $sCamposSql = split('#', $sCampos);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++){

        $sSql .= $sVirgula.$sCamposSql[$iCont];
        $virgula = ",";

      }

    } else {
      $sSql .= $sCampos;
    }
    $sSql .= " from historico ";
    $sSql .= "      inner join aluno on ed47_i_codigo = ed61_i_aluno ";
    $sSql .= "      left join alunocurso on ed56_i_aluno = ed47_i_codigo ";
    $sSql .= "      left join historicomps on ed62_i_historico = ed61_i_codigo ";
    $sSql .= "      left join historicompsfora on ed99_i_historico = ed61_i_codigo ";
    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where historico.ed61_i_codigo = $iCodigo ";
      }

    } elseif ($sDbWhere != '') {
      $sSql2 = " where $sDbWhere";
    }
    $sSql .= $sSql2;

    if ($sOrdem != null) {

      $sSql      .= ' order by ';
      $sCamposSql = split('#', $sOrdem);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++) {

        $sSql    .= $sVirgula.$sCamposSql[$iCont];
        $sVirgula = ',';

      }

    }

    return $sSql;

  }

  function sql_query_historico ($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

    $sSql = 'select ';
    if ($sCampos != '*') {

      $sCamposSql = split('#', $sCampos);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++){

        $sSql .= $sVirgula.$sCamposSql[$iCont];
        $virgula = ",";

      }

    } else {
      $sSql .= $sCampos;
    }
    $sSql .= " from historico ";
    $sSql .= "      left join historicomps on ed62_i_historico = ed61_i_codigo ";
    $sSql .= "      left join historicompsfora on ed99_i_historico = ed61_i_codigo ";
    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where historico.ed61_i_codigo = $iCodigo ";
      }

    } elseif ($sDbWhere != '') {
      $sSql2 = " where $sDbWhere";
    }
    $sSql .= $sSql2;

    if ($sOrdem != null) {

      $sSql      .= ' order by ';
      $sCamposSql = split('#', $sOrdem);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++) {

        $sSql    .= $sVirgula.$sCamposSql[$iCont];
        $sVirgula = ',';

      }

    }

    return $sSql;

  }

  function sql_query_historicomps ($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

    $sSql = 'select ';
    if ($sCampos != '*') {

      $sCamposSql = split('#', $sCampos);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++){

        $sSql .= $sVirgula.$sCamposSql[$iCont];
        $virgula = ",";

      }

    } else {
      $sSql .= $sCampos;
    }
    $sSql .= " from historico ";
    $sSql .= "      left join historicomps on ed62_i_historico = ed61_i_codigo ";
    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where historico.ed61_i_codigo = $iCodigo ";
      }

    } elseif ($sDbWhere != '') {
      $sSql2 = " where $sDbWhere";
    }
    $sSql .= $sSql2;

    if ($sOrdem != null) {

      $sSql      .= ' order by ';
      $sCamposSql = split('#', $sOrdem);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++) {

        $sSql    .= $sVirgula.$sCamposSql[$iCont];
        $sVirgula = ',';

      }

    }

    return $sSql;

  }

function sql_query_historicompsfora ($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

    $sSql = 'select ';
    if ($sCampos != '*') {

      $sCamposSql = split('#', $sCampos);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++){

        $sSql .= $sVirgula.$sCamposSql[$iCont];
        $virgula = ",";

      }

    } else {
      $sSql .= $sCampos;
    }
    $sSql .= " from historico ";
    $sSql .= "     left join historicompsfora on ed99_i_historico = ed61_i_codigo ";
    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where historico.ed61_i_codigo = $iCodigo ";
      }

    } elseif ($sDbWhere != '') {
      $sSql2 = " where $sDbWhere";
    }
    $sSql .= $sSql2;

    if ($sOrdem != null) {

      $sSql      .= ' order by ';
      $sCamposSql = split('#', $sOrdem);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++) {

        $sSql    .= $sVirgula.$sCamposSql[$iCont];
        $sVirgula = ',';

      }

    }

    return $sSql;

  }

function sql_query_uniondisciplina($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

    $sSql = 'select ';
    if ($sCampos != '*') {

      $sCamposSql = split('#', $sCampos);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++){

        $sSql .= $sVirgula.$sCamposSql[$iCont];
        $virgula = ",";

      }

    } else {
      $sSql .= $sCampos;
    }
    $sSql .= '  from ((select true as forarede, ed62_i_anoref as ano, ed62_c_resultadofinal as resfinal, ';
    $sSql .= '                serie.*, historico.* ';
    $sSql .= '             from historico ';
    $sSql .= '                  inner join historicomps on historicomps.ed62_i_historico = historico.ed61_i_codigo ';
    $sSql .= '                  inner join histmpsdisc on histmpsdisc.ed65_i_historicomps = historicomps.ed62_i_codigo ';
    $sSql .= '    inner join serie on serie.ed11_i_codigo = historicomps.ed62_i_serie ) ';
    $sSql .= '                 union ';
    $sSql .= '           (select false as forarede, ed99_i_anoref as ano, ed99_c_resultadofinal as resfinal, ';
    $sSql .= '                   serie.*, historico.* ';
    $sSql .= '              from historico ';
    $sSql .= '              inner join historicompsfora on    historicompsfora.ed99_i_historico = historico.ed61_i_codigo ';
    $sSql .= '              inner join histmpsdiscfora on    histmpsdiscfora.ed100_i_historicompsfora = historicompsfora.ed99_i_codigo ';
    $sSql .= '              inner join serie on serie.ed11_i_codigo = historicompsfora.ed99_i_serie)) as a ';
    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where historico.ed61_i_codigo = $iCodigo "; // Alterar aqui
      }

    } elseif ($sDbWhere != '') {
      $sSql2 = " where $sDbWhere";
    }
    $sSql .= $sSql2;

    if ($sOrdem != null) {

      $sSql      .= ' order by ';
      $sCamposSql = split('#', $sOrdem);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++) {

        $sSql    .= $sVirgula.$sCamposSql[$iCont];
        $sVirgula = ',';

      }

    }

    return $sSql;

  }

function sql_query_etapas_historico($iCodigo, $sCampos, $sOrdem = null) {

    $sSql = 'select ';
    if ($sCampos != '*') {

      $sCamposSql = split('#', $sCampos);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++){

        $sSql .= $sVirgula.$sCamposSql[$iCont];
        $virgula = ",";

      }

    } else {
      $sSql .= $sCampos;
    }
    $sSql .= '  from ((select 1 as tipo , ed62_i_codigo  as codigo, ed62_i_anoref as ano';
    $sSql .= '                  from  historicomps ';
    $sSql .= "                where  ed62_i_historico = {$iCodigo}) ";
    $sSql .= '                 union ';
    $sSql .= '        (select 2 as tipo, ed99_i_codigo  as codigo, ed99_i_anoref as ano';
    $sSql .= '              from historicompsfora ';
    $sSql .= "                where  ed99_i_historico = {$iCodigo}) ";
    $sSql .= ' ) as a ';

    if ($sOrdem != null) {

      $sSql      .= ' order by ';
      $sCamposSql = split('#', $sOrdem);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++) {

        $sSql    .= $sVirgula.$sCamposSql[$iCont];
        $sVirgula = ',';

      }

    }

    return $sSql;

  }


  /**
   * Retorna dados da estapa(fora ou dentro da rede) conforme o código do aluno.
   *
   * @param  Integer $iCodigoAluno
   * @param  String  $sCampos
   * @param  String  $sOrdem
   * @return String
   */
  function sql_query_etapasHistoricoPorAluno($iCodigoAluno, $sCamposSemSigla = null, $sOrdem = null) {

    if ( empty($sCamposSemSigla) ) {
      $sCamposSemSigla = "*";
    }

    $sSql  = "select {$sCamposSemSigla}";
    $sSql .= "  from ( select false as fora_rede,                                 ";
    $sSql .= "                ed62_i_codigo               as codigo,              ";
    $sSql .= "                ed62_i_historico            as historico,           ";
    $sSql .= "                ed62_i_escola               as escola,              ";
    $sSql .= "                ed62_i_serie                as serie,               ";
    $sSql .= "                ed62_i_turma                as turma,               ";
    $sSql .= "                ed62_i_justificativa        as justificativa,       ";
    $sSql .= "                ed62_i_anoref               as anoref,              ";
    $sSql .= "                ed62_i_periodoref           as periodoref,          ";
    $sSql .= "                ed62_c_resultadofinal       as resultadofinal,      ";
    $sSql .= "                ed62_c_situacao             as situacao,            ";
    $sSql .= "                ed62_i_qtdch                as qtdch,               ";
    $sSql .= "                ed62_i_diasletivos          as diasletivos,         ";
    $sSql .= "                ed62_c_minimo               as minimo,              ";
    $sSql .= "                ed62_c_termofinal           as termofinal,          ";
    $sSql .= "                ed62_lancamentoautomatico   as lancamentoautomatico,";
    $sSql .= "                ed62_percentualfrequencia   as percentualfrequencia,";
    $sSql .= "                ed62_observacao             as observacao           ";
    $sSql .= "           from historicomps                                        ";
    $sSql .= "          inner join historico on historico.ed61_i_codigo = historicomps.ed62_i_historico";
    $sSql .= "          where ed61_i_aluno = {$iCodigoAluno}                      ";
    $sSql .= "         union                                                      ";
    $sSql .= "         select true as fora_rede,                                  ";
    $sSql .= "                ed99_i_codigo               as codigo,              ";
    $sSql .= "                ed99_i_historico            as historico,           ";
    $sSql .= "                ed99_i_escolaproc           as escola,              ";
    $sSql .= "                ed99_i_serie                as serie,               ";
    $sSql .= "                ed99_c_turma                as turma,               ";
    $sSql .= "                ed99_i_justificativa        as justificativa,       ";
    $sSql .= "                ed99_i_anoref               as anoref,              ";
    $sSql .= "                ed99_i_periodoref           as periodoref,          ";
    $sSql .= "                ed99_c_resultadofinal       as resultadofinal,      ";
    $sSql .= "                ed99_c_situacao             as situacao,            ";
    $sSql .= "                ed99_i_qtdch                as qtdch,               ";
    $sSql .= "                ed99_i_diasletivos          as diasletivos,         ";
    $sSql .= "                ed99_c_minimo               as minimo,              ";
    $sSql .= "                ed99_c_termofinal           as termofinal,          ";
    $sSql .= "                false                       as lancamentoautomatico,";
    $sSql .= "                null                        as percentualfrequencia,";
    $sSql .= "                ed99_observacao             as observacao           ";
    $sSql .= "           from historicompsfora                                    ";
    $sSql .= "          inner join historico on historico.ed61_i_codigo = historicompsfora.ed99_i_historico";
    $sSql .= "          where ed61_i_aluno = {$iCodigoAluno}                     ";
    $sSql .= "       ) as a ";

    if ( !empty($sOrdem) ) {
      $sSql .= " order by {$sOrdem}";
    }

    return $sSql;
  }
}