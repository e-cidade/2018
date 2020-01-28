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

//MODULO: escola
//CLASSE DA ENTIDADE base
class cl_base {
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
   var $ed31_i_codigo = 0;
   var $ed31_i_curso = 0;
   var $ed31_c_descr = null;
   var $ed31_c_turno = null;
   var $ed31_c_medfreq = null;
   var $ed31_c_contrfreq = null;
   var $ed31_t_obs = null;
   var $ed31_c_conclusao = null;
   var $ed31_i_regimemat = 0;
   var $ed31_c_ativo = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 ed31_i_codigo = int8 = Código
                 ed31_i_curso = int8 = Curso
                 ed31_c_descr = char(40) = Nome da Base
                 ed31_c_turno = char(20) = Turno
                 ed31_c_medfreq = char(1) = Frequência
                 ed31_c_contrfreq = char(1) = Controle de Frequência
                 ed31_t_obs = text = Observações
                 ed31_c_conclusao = char(1) = Base conclui curso
                 ed31_i_regimemat = int8 = Regime de Matrícula
                 ed31_c_ativo = char(1) = Ativa
                 ";
   //funcao construtor da classe
   function cl_base() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("base");
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
       $this->ed31_i_codigo = ($this->ed31_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed31_i_codigo"]:$this->ed31_i_codigo);
       $this->ed31_i_curso = ($this->ed31_i_curso == ""?@$GLOBALS["HTTP_POST_VARS"]["ed31_i_curso"]:$this->ed31_i_curso);
       $this->ed31_c_descr = ($this->ed31_c_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["ed31_c_descr"]:$this->ed31_c_descr);
       $this->ed31_c_turno = ($this->ed31_c_turno == ""?@$GLOBALS["HTTP_POST_VARS"]["ed31_c_turno"]:$this->ed31_c_turno);
       $this->ed31_c_medfreq = ($this->ed31_c_medfreq == ""?@$GLOBALS["HTTP_POST_VARS"]["ed31_c_medfreq"]:$this->ed31_c_medfreq);
       $this->ed31_c_contrfreq = ($this->ed31_c_contrfreq == ""?@$GLOBALS["HTTP_POST_VARS"]["ed31_c_contrfreq"]:$this->ed31_c_contrfreq);
       $this->ed31_t_obs = ($this->ed31_t_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["ed31_t_obs"]:$this->ed31_t_obs);
       $this->ed31_c_conclusao = ($this->ed31_c_conclusao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed31_c_conclusao"]:$this->ed31_c_conclusao);
       $this->ed31_i_regimemat = ($this->ed31_i_regimemat == ""?@$GLOBALS["HTTP_POST_VARS"]["ed31_i_regimemat"]:$this->ed31_i_regimemat);
       $this->ed31_c_ativo = ($this->ed31_c_ativo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed31_c_ativo"]:$this->ed31_c_ativo);
     }else{
       $this->ed31_i_codigo = ($this->ed31_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed31_i_codigo"]:$this->ed31_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed31_i_codigo){
      $this->atualizacampos();
     if($this->ed31_i_curso == null ){
       $this->erro_sql = " Campo Curso nao Informado.";
       $this->erro_campo = "ed31_i_curso";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed31_c_descr == null ){
       $this->erro_sql = " Campo Nome da Base nao Informado.";
       $this->erro_campo = "ed31_c_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed31_c_turno == null ){
       $this->erro_sql = " Campo Turno nao Informado.";
       $this->erro_campo = "ed31_c_turno";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed31_c_medfreq == null ){
       $this->erro_sql = " Campo Frequência nao Informado.";
       $this->erro_campo = "ed31_c_medfreq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed31_c_conclusao == null ){
       $this->erro_sql = " Campo Base conclui curso nao Informado.";
       $this->erro_campo = "ed31_c_conclusao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed31_i_regimemat == null ){
       $this->erro_sql = " Campo Regime de Matrícula nao Informado.";
       $this->erro_campo = "ed31_i_regimemat";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed31_c_ativo == null ){
       $this->erro_sql = " Campo Ativa nao Informado.";
       $this->erro_campo = "ed31_c_ativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed31_i_codigo == "" || $ed31_i_codigo == null ){
       $result = db_query("select nextval('base_ed31_i_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: base_ed31_i_codigo_seq do campo: ed31_i_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->ed31_i_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from base_ed31_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed31_i_codigo)){
         $this->erro_sql = " Campo ed31_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed31_i_codigo = $ed31_i_codigo;
       }
     }
     if(($this->ed31_i_codigo == null) || ($this->ed31_i_codigo == "") ){
       $this->erro_sql = " Campo ed31_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into base(
                                       ed31_i_codigo
                                      ,ed31_i_curso
                                      ,ed31_c_descr
                                      ,ed31_c_turno
                                      ,ed31_c_medfreq
                                      ,ed31_c_contrfreq
                                      ,ed31_t_obs
                                      ,ed31_c_conclusao
                                      ,ed31_i_regimemat
                                      ,ed31_c_ativo
                       )
                values (
                                $this->ed31_i_codigo
                               ,$this->ed31_i_curso
                               ,'$this->ed31_c_descr'
                               ,'$this->ed31_c_turno'
                               ,'$this->ed31_c_medfreq'
                               ,'$this->ed31_c_contrfreq'
                               ,'$this->ed31_t_obs'
                               ,'$this->ed31_c_conclusao'
                               ,$this->ed31_i_regimemat
                               ,'$this->ed31_c_ativo'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Base Curricular ($this->ed31_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Base Curricular já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Base Curricular ($this->ed31_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed31_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed31_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1008354,'$this->ed31_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1010060,1008354,'','".AddSlashes(pg_result($resaco,0,'ed31_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010060,1008355,'','".AddSlashes(pg_result($resaco,0,'ed31_i_curso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010060,1008356,'','".AddSlashes(pg_result($resaco,0,'ed31_c_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010060,1008357,'','".AddSlashes(pg_result($resaco,0,'ed31_c_turno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010060,1008363,'','".AddSlashes(pg_result($resaco,0,'ed31_c_medfreq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010060,1008364,'','".AddSlashes(pg_result($resaco,0,'ed31_c_contrfreq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010060,1008367,'','".AddSlashes(pg_result($resaco,0,'ed31_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010060,1008821,'','".AddSlashes(pg_result($resaco,0,'ed31_c_conclusao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010060,14961,'','".AddSlashes(pg_result($resaco,0,'ed31_i_regimemat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010060,15013,'','".AddSlashes(pg_result($resaco,0,'ed31_c_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($ed31_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update base set ";
     $virgula = "";
     if(trim($this->ed31_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed31_i_codigo"])){
       $sql  .= $virgula." ed31_i_codigo = $this->ed31_i_codigo ";
       $virgula = ",";
       if(trim($this->ed31_i_codigo) == null ){
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed31_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed31_i_curso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed31_i_curso"])){
       $sql  .= $virgula." ed31_i_curso = $this->ed31_i_curso ";
       $virgula = ",";
       if(trim($this->ed31_i_curso) == null ){
         $this->erro_sql = " Campo Curso nao Informado.";
         $this->erro_campo = "ed31_i_curso";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed31_c_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed31_c_descr"])){
       $sql  .= $virgula." ed31_c_descr = '$this->ed31_c_descr' ";
       $virgula = ",";
       if(trim($this->ed31_c_descr) == null ){
         $this->erro_sql = " Campo Nome da Base nao Informado.";
         $this->erro_campo = "ed31_c_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed31_c_turno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed31_c_turno"])){
       $sql  .= $virgula." ed31_c_turno = '$this->ed31_c_turno' ";
       $virgula = ",";
       if(trim($this->ed31_c_turno) == null ){
         $this->erro_sql = " Campo Turno nao Informado.";
         $this->erro_campo = "ed31_c_turno";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed31_c_medfreq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed31_c_medfreq"])){
       $sql  .= $virgula." ed31_c_medfreq = '$this->ed31_c_medfreq' ";
       $virgula = ",";
       if(trim($this->ed31_c_medfreq) == null ){
         $this->erro_sql = " Campo Frequência nao Informado.";
         $this->erro_campo = "ed31_c_medfreq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed31_c_contrfreq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed31_c_contrfreq"])){
       $sql  .= $virgula." ed31_c_contrfreq = '$this->ed31_c_contrfreq' ";
       $virgula = ",";
     }
     if(trim($this->ed31_t_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed31_t_obs"])){
       $sql  .= $virgula." ed31_t_obs = '$this->ed31_t_obs' ";
       $virgula = ",";
     }
     if(trim($this->ed31_c_conclusao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed31_c_conclusao"])){
       $sql  .= $virgula." ed31_c_conclusao = '$this->ed31_c_conclusao' ";
       $virgula = ",";
       if(trim($this->ed31_c_conclusao) == null ){
         $this->erro_sql = " Campo Base conclui curso nao Informado.";
         $this->erro_campo = "ed31_c_conclusao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed31_i_regimemat)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed31_i_regimemat"])){
       $sql  .= $virgula." ed31_i_regimemat = $this->ed31_i_regimemat ";
       $virgula = ",";
       if(trim($this->ed31_i_regimemat) == null ){
         $this->erro_sql = " Campo Regime de Matrícula nao Informado.";
         $this->erro_campo = "ed31_i_regimemat";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed31_c_ativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed31_c_ativo"])){
       $sql  .= $virgula." ed31_c_ativo = '$this->ed31_c_ativo' ";
       $virgula = ",";
       if(trim($this->ed31_c_ativo) == null ){
         $this->erro_sql = " Campo Ativa nao Informado.";
         $this->erro_campo = "ed31_c_ativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed31_i_codigo!=null){
       $sql .= " ed31_i_codigo = $this->ed31_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed31_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008354,'$this->ed31_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed31_i_codigo"]) || $this->ed31_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,1010060,1008354,'".AddSlashes(pg_result($resaco,$conresaco,'ed31_i_codigo'))."','$this->ed31_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed31_i_curso"]) || $this->ed31_i_curso != "")
           $resac = db_query("insert into db_acount values($acount,1010060,1008355,'".AddSlashes(pg_result($resaco,$conresaco,'ed31_i_curso'))."','$this->ed31_i_curso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed31_c_descr"]) || $this->ed31_c_descr != "")
           $resac = db_query("insert into db_acount values($acount,1010060,1008356,'".AddSlashes(pg_result($resaco,$conresaco,'ed31_c_descr'))."','$this->ed31_c_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed31_c_turno"]) || $this->ed31_c_turno != "")
           $resac = db_query("insert into db_acount values($acount,1010060,1008357,'".AddSlashes(pg_result($resaco,$conresaco,'ed31_c_turno'))."','$this->ed31_c_turno',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed31_c_medfreq"]) || $this->ed31_c_medfreq != "")
           $resac = db_query("insert into db_acount values($acount,1010060,1008363,'".AddSlashes(pg_result($resaco,$conresaco,'ed31_c_medfreq'))."','$this->ed31_c_medfreq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed31_c_contrfreq"]) || $this->ed31_c_contrfreq != "")
           $resac = db_query("insert into db_acount values($acount,1010060,1008364,'".AddSlashes(pg_result($resaco,$conresaco,'ed31_c_contrfreq'))."','$this->ed31_c_contrfreq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed31_t_obs"]) || $this->ed31_t_obs != "")
           $resac = db_query("insert into db_acount values($acount,1010060,1008367,'".AddSlashes(pg_result($resaco,$conresaco,'ed31_t_obs'))."','$this->ed31_t_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed31_c_conclusao"]) || $this->ed31_c_conclusao != "")
           $resac = db_query("insert into db_acount values($acount,1010060,1008821,'".AddSlashes(pg_result($resaco,$conresaco,'ed31_c_conclusao'))."','$this->ed31_c_conclusao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed31_i_regimemat"]) || $this->ed31_i_regimemat != "")
           $resac = db_query("insert into db_acount values($acount,1010060,14961,'".AddSlashes(pg_result($resaco,$conresaco,'ed31_i_regimemat'))."','$this->ed31_i_regimemat',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed31_c_ativo"]) || $this->ed31_c_ativo != "")
           $resac = db_query("insert into db_acount values($acount,1010060,15013,'".AddSlashes(pg_result($resaco,$conresaco,'ed31_c_ativo'))."','$this->ed31_c_ativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Base Curricular nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed31_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Base Curricular nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed31_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed31_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($ed31_i_codigo=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed31_i_codigo));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008354,'$ed31_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1010060,1008354,'','".AddSlashes(pg_result($resaco,$iresaco,'ed31_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010060,1008355,'','".AddSlashes(pg_result($resaco,$iresaco,'ed31_i_curso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010060,1008356,'','".AddSlashes(pg_result($resaco,$iresaco,'ed31_c_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010060,1008357,'','".AddSlashes(pg_result($resaco,$iresaco,'ed31_c_turno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010060,1008363,'','".AddSlashes(pg_result($resaco,$iresaco,'ed31_c_medfreq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010060,1008364,'','".AddSlashes(pg_result($resaco,$iresaco,'ed31_c_contrfreq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010060,1008367,'','".AddSlashes(pg_result($resaco,$iresaco,'ed31_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010060,1008821,'','".AddSlashes(pg_result($resaco,$iresaco,'ed31_c_conclusao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010060,14961,'','".AddSlashes(pg_result($resaco,$iresaco,'ed31_i_regimemat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010060,15013,'','".AddSlashes(pg_result($resaco,$iresaco,'ed31_c_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from base
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed31_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed31_i_codigo = $ed31_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Base Curricular nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed31_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Base Curricular nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed31_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed31_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:base";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $ed31_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from base ";
     $sql .= "      inner join cursoedu on cursoedu.ed29_i_codigo = base.ed31_i_curso";
     $sql .= "      inner join ensino on ensino.ed10_i_codigo = cursoedu.ed29_i_ensino";
     $sql .= "      inner join regimemat  on  regimemat.ed218_i_codigo = base.ed31_i_regimemat";
     $sql .= "      left join cursoescola on cursoescola.ed71_i_curso = cursoedu.ed29_i_codigo";
     $sql .= "      left join baseserie on baseserie.ed87_i_codigo = base.ed31_i_codigo";
     $sql .= "      left join serie as si on si.ed11_i_codigo = baseserie.ed87_i_serieinicial";
     $sql .= "      left join serie as sf on sf.ed11_i_codigo = baseserie.ed87_i_seriefinal";
     $sql .= "      left join basediscglob on  basediscglob.ed89_i_codigo = base.ed31_i_codigo";
     $sql .= "      left join disciplina on disciplina.ed12_i_codigo = basediscglob.ed89_i_disciplina";
     $sql .= "      left join caddisciplina on ed232_i_codigo= ed12_i_caddisciplina";
     $sql2 = "";
     if($dbwhere==""){
       if($ed31_i_codigo!=null ){
         $sql2 .= " where base.ed31_i_codigo = $ed31_i_codigo ";
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
   function sql_query_file ( $ed31_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from base ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed31_i_codigo!=null ){
         $sql2 .= " where base.ed31_i_codigo = $ed31_i_codigo ";
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
  function sql_query_base ( $ed31_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from base ";
     $sql .= "      inner join cursoedu on cursoedu.ed29_i_codigo = base.ed31_i_curso";
     $sql .= "      inner join ensino on ensino.ed10_i_codigo = cursoedu.ed29_i_ensino";
     $sql .= "      inner join regimemat  on  regimemat.ed218_i_codigo = base.ed31_i_regimemat";
     $sql .= "      inner join escolabase on escolabase.ed77_i_base = base.ed31_i_codigo";
     $sql .= "      left join baseserie on baseserie.ed87_i_codigo = base.ed31_i_codigo";
     $sql .= "      left join serie as si on si.ed11_i_codigo = baseserie.ed87_i_serieinicial";
     $sql .= "      left join serie as sf on sf.ed11_i_codigo = baseserie.ed87_i_seriefinal";
     $sql .= "      left join basediscglob on  basediscglob.ed89_i_codigo = base.ed31_i_codigo";
     $sql .= "      left join disciplina on disciplina.ed12_i_codigo = basediscglob.ed89_i_disciplina";
     $sql .= "      left join caddisciplina on ed232_i_codigo= ed12_i_caddisciplina";
     $sql2 = "";
     if($dbwhere==""){
       if($ed31_i_codigo!=null ){
         $sql2 .= " where base.ed31_i_codigo = $ed31_i_codigo ";
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

  function sql_query_baseturma ( $ed31_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from base ";
     $sql .= "      inner join cursoedu on cursoedu.ed29_i_codigo = base.ed31_i_curso";
     $sql .= "      inner join cursoescola on cursoescola.ed71_i_curso = cursoedu.ed29_i_codigo";
     $sql .= "      inner join ensino on ensino.ed10_i_codigo = cursoedu.ed29_i_ensino";
     $sql .= "      inner join tipoensino on tipoensino.ed36_i_codigo = ensino.ed10_i_tipoensino";
     $sql .= "      inner join escolabase on escolabase.ed77_i_base = base.ed31_i_codigo";
     $sql .= "      inner join regimemat  on  regimemat.ed218_i_codigo = base.ed31_i_regimemat";
     $sql .= "      left join baseserie on baseserie.ed87_i_codigo = base.ed31_i_codigo";
     $sql .= "      left join serie as si on si.ed11_i_codigo = baseserie.ed87_i_serieinicial";
     $sql .= "      left join serie as sf on sf.ed11_i_codigo = baseserie.ed87_i_seriefinal";
     $sql .= "      left join basediscglob on  basediscglob.ed89_i_codigo = base.ed31_i_codigo";
     $sql .= "      left join disciplina on disciplina.ed12_i_codigo = basediscglob.ed89_i_disciplina";
     $sql .= "      left join caddisciplina on ed232_i_codigo= ed12_i_caddisciplina";
     $sql2 = "";
     if($dbwhere==""){
       if($ed31_i_codigo!=null ){
         $sql2 .= " where base.ed31_i_codigo = $ed31_i_codigo ";
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
   function sql_query_hist ( $ed31_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from base ";
     $sql .= "      inner join cursoedu on cursoedu.ed29_i_codigo = base.ed31_i_curso";
     $sql .= "      inner join cursoescola on cursoescola.ed71_i_curso = cursoedu.ed29_i_codigo";
     $sql .= "      inner join ensino on ensino.ed10_i_codigo = cursoedu.ed29_i_ensino";
     $sql .= "      inner join basemps on basemps.ed34_i_base = base.ed31_i_codigo";
     $sql .= "      inner join escolabase on escolabase.ed77_i_base = base.ed31_i_codigo";
     $sql .= "      inner join regimemat  on  regimemat.ed218_i_codigo = base.ed31_i_regimemat";
     $sql2 = "";
     if($dbwhere==""){
       if($ed31_i_codigo!=null ){
         $sql2 .= " where base.ed31_i_codigo = $ed31_i_codigo ";
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

  function sql_query_diarioclasse ( $ed31_i_codigo=null,$campos="*",$ordem=null,$dbwhere="") {

    $sql = "select ";
    if ($campos != "*" ) {

      $campos_sql = split("#",$campos);
      $virgula = "";
      for ($i=0; $i<sizeof($campos_sql); $i++) {

        $sql     .= $virgula.$campos_sql[$i];
        $virgula  = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from base ";
    $sql .= "      inner join turma      on ed57_i_base = ed31_i_codigo";
    $sql .= "      inner join matricula  on ed60_i_turma = ed57_i_codigo";
    $sql .= "      inner join escolabase on ed77_i_base = ed31_i_codigo";
    $sql .= "      inner join regimemat  on ed218_i_codigo = ed31_i_regimemat";
    $sql2 = "";
    if ($dbwhere == "") {

      if ($ed31_i_codigo != null ) {
        $sql2 .= " where base.ed31_i_codigo = $ed31_i_codigo ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null ) {

      $sql        .= " order by ";
      $campos_sql  = split("#",$ordem);
      $virgula     = "";
      for ($i=0; $i<sizeof($campos_sql); $i++) {

        $sql     .= $virgula.$campos_sql[$i];
        $virgula  = ",";
      }
    }
    return $sql;
  }


  function sql_query_base2 ( $ed31_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){

     $sql  = "select {$campos} ";
     $sql .= " from base ";
     $sql .= "      inner join cursoedu on cursoedu.ed29_i_codigo = base.ed31_i_curso";
     $sql .= "      inner join ensino on ensino.ed10_i_codigo = cursoedu.ed29_i_ensino";
     $sql .= "      inner join regimemat  on  regimemat.ed218_i_codigo = base.ed31_i_regimemat";
     $sql .= "      left  join escolabase on escolabase.ed77_i_base = base.ed31_i_codigo";
     $sql .= "      left  join baseserie on baseserie.ed87_i_codigo = base.ed31_i_codigo";
     $sql .= "      left  join serie as si on si.ed11_i_codigo = baseserie.ed87_i_serieinicial";
     $sql .= "      left  join serie as sf on sf.ed11_i_codigo = baseserie.ed87_i_seriefinal";
     $sql .= "      left  join basediscglob on  basediscglob.ed89_i_codigo = base.ed31_i_codigo";
     $sql .= "      left  join disciplina on disciplina.ed12_i_codigo = basediscglob.ed89_i_disciplina";
     $sql .= "      left  join caddisciplina on ed232_i_codigo= ed12_i_caddisciplina";
     $sql2 = "";

    if ($dbwhere == "") {

      if ( $ed31_i_codigo!=null ) {
        $sql2 .= " where base.ed31_i_codigo = $ed31_i_codigo ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if (!empty($ordem)) {
      $sql .= " order by {$ordem}";
    }

    return $sql;
  }
}
?>