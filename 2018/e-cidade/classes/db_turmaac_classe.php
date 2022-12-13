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
//CLASSE DA ENTIDADE turmaac
class cl_turmaac {
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
   var $ed268_i_codigo = 0;
   var $ed268_i_codigoinep = 0;
   var $ed268_i_escola = 0;
   var $ed268_i_calendario = 0;
   var $ed268_c_descr = null;
   var $ed268_i_turno = 0;
   var $ed268_i_sala = 0;
   var $ed268_i_numvagas = 0;
   var $ed268_i_nummatr = 0;
   var $ed268_t_obs = null;
   var $ed268_i_tipoatend = 0;
   var $ed268_i_ativqtd = 0;
   var $ed268_c_aee = null;
   var $ed268_programamaiseducacao = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 ed268_i_codigo = int8 = Código
                 ed268_i_codigoinep = int4 = Código INEP
                 ed268_i_escola = int8 = Escola
                 ed268_i_calendario = int8 = Calendário
                 ed268_c_descr = char(80) = Nome da Turma
                 ed268_i_turno = int8 = Turno
                 ed268_i_sala = int8 = Dependência
                 ed268_i_numvagas = int8 = Vagas
                 ed268_i_nummatr = int4 = Alunos Matriculados
                 ed268_t_obs = text = Observações
                 ed268_i_tipoatend = int4 = Tipo de Atendimento
                 ed268_i_ativqtd = int4 = Qtde. vezes da Atividade / AEE
                 ed268_c_aee = char(20) = Tipo de Atendimento Educ. Especial - AEE
                 ed268_programamaiseducacao = int4 = Programa mais Educação
                 ";
   //funcao construtor da classe
   function cl_turmaac() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("turmaac");
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
       $this->ed268_i_codigo = ($this->ed268_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed268_i_codigo"]:$this->ed268_i_codigo);
       $this->ed268_i_codigoinep = ($this->ed268_i_codigoinep == ""?@$GLOBALS["HTTP_POST_VARS"]["ed268_i_codigoinep"]:$this->ed268_i_codigoinep);
       $this->ed268_i_escola = ($this->ed268_i_escola == ""?@$GLOBALS["HTTP_POST_VARS"]["ed268_i_escola"]:$this->ed268_i_escola);
       $this->ed268_i_calendario = ($this->ed268_i_calendario == ""?@$GLOBALS["HTTP_POST_VARS"]["ed268_i_calendario"]:$this->ed268_i_calendario);
       $this->ed268_c_descr = ($this->ed268_c_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["ed268_c_descr"]:$this->ed268_c_descr);
       $this->ed268_i_turno = ($this->ed268_i_turno == ""?@$GLOBALS["HTTP_POST_VARS"]["ed268_i_turno"]:$this->ed268_i_turno);
       $this->ed268_i_sala = ($this->ed268_i_sala == ""?@$GLOBALS["HTTP_POST_VARS"]["ed268_i_sala"]:$this->ed268_i_sala);
       $this->ed268_i_numvagas = ($this->ed268_i_numvagas == ""?@$GLOBALS["HTTP_POST_VARS"]["ed268_i_numvagas"]:$this->ed268_i_numvagas);
       $this->ed268_i_nummatr = ($this->ed268_i_nummatr == ""?@$GLOBALS["HTTP_POST_VARS"]["ed268_i_nummatr"]:$this->ed268_i_nummatr);
       $this->ed268_t_obs = ($this->ed268_t_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["ed268_t_obs"]:$this->ed268_t_obs);
       $this->ed268_i_tipoatend = ($this->ed268_i_tipoatend == ""?@$GLOBALS["HTTP_POST_VARS"]["ed268_i_tipoatend"]:$this->ed268_i_tipoatend);
       $this->ed268_i_ativqtd = ($this->ed268_i_ativqtd == ""?@$GLOBALS["HTTP_POST_VARS"]["ed268_i_ativqtd"]:$this->ed268_i_ativqtd);
       $this->ed268_c_aee = ($this->ed268_c_aee == ""?@$GLOBALS["HTTP_POST_VARS"]["ed268_c_aee"]:$this->ed268_c_aee);
     }else{
       $this->ed268_i_codigo = ($this->ed268_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed268_i_codigo"]:$this->ed268_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed268_i_codigo){
      $this->atualizacampos();
     if($this->ed268_i_codigoinep == null ){
       $this->ed268_i_codigoinep = "null";
     }
     if($this->ed268_i_escola == null ){
       $this->erro_sql = " Campo Escola nao Informado.";
       $this->erro_campo = "ed268_i_escola";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed268_i_calendario == null ){
       $this->erro_sql = " Campo Calendário nao Informado.";
       $this->erro_campo = "ed268_i_calendario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed268_c_descr == null ){
       $this->erro_sql = " Campo Nome da Turma nao Informado.";
       $this->erro_campo = "ed268_c_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed268_i_turno == null ){
       $this->erro_sql = " Campo Turno nao Informado.";
       $this->erro_campo = "ed268_i_turno";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed268_i_sala == null ){
       $this->ed268_i_sala = "null";
     }
     if($this->ed268_i_numvagas == null ){
       $this->erro_sql = " Campo Vagas nao Informado.";
       $this->erro_campo = "ed268_i_numvagas";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed268_i_nummatr == null ){
       $this->ed268_i_nummatr = "null";
     }
     if($this->ed268_i_tipoatend == null ){
       $this->erro_sql = " Campo Tipo de Atendimento nao Informado.";
       $this->erro_campo = "ed268_i_tipoatend";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed268_i_ativqtd == null ){
       $this->erro_sql = " Campo Qtde. vezes da Atividade / AEE nao Informado.";
       $this->erro_campo = "ed268_i_ativqtd";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed268_c_aee == null ){
       $this->ed268_c_aee = "null";
     }
     if($this->ed268_programamaiseducacao == null ){
       $this->ed268_programamaiseducacao = "null";
     }
     if($ed268_i_codigo == "" || $ed268_i_codigo == null ){
       $result = db_query("select nextval('turma_ed57_i_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: turma_ed57_i_codigo_seq do campo: ed268_i_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->ed268_i_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from turma_ed57_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed268_i_codigo)){
         $this->erro_sql = " Campo ed268_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed268_i_codigo = $ed268_i_codigo;
       }
     }
     if(($this->ed268_i_codigo == null) || ($this->ed268_i_codigo == "") ){
       $this->erro_sql = " Campo ed268_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into turmaac(
                                       ed268_i_codigo
                                      ,ed268_i_codigoinep
                                      ,ed268_i_escola
                                      ,ed268_i_calendario
                                      ,ed268_c_descr
                                      ,ed268_i_turno
                                      ,ed268_i_sala
                                      ,ed268_i_numvagas
                                      ,ed268_i_nummatr
                                      ,ed268_t_obs
                                      ,ed268_i_tipoatend
                                      ,ed268_i_ativqtd
                                      ,ed268_c_aee
                                      ,ed268_programamaiseducacao
                       )
                values (
                                $this->ed268_i_codigo
                               ,$this->ed268_i_codigoinep
                               ,$this->ed268_i_escola
                               ,$this->ed268_i_calendario
                               ,'$this->ed268_c_descr'
                               ,$this->ed268_i_turno
                               ,$this->ed268_i_sala
                               ,$this->ed268_i_numvagas
                               ,$this->ed268_i_nummatr
                               ,'$this->ed268_t_obs'
                               ,$this->ed268_i_tipoatend
                               ,$this->ed268_i_ativqtd
                               ,'$this->ed268_c_aee'
                               ,$this->ed268_programamaiseducacao
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Turma com Atividade Complementar / AEE ($this->ed268_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Turma com Atividade Complementar / AEE já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Turma com Atividade Complementar / AEE ($this->ed268_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed268_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed268_i_codigo  ));
     if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13824,'$this->ed268_i_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,2416,13824,'','".AddSlashes(pg_result($resaco,0,'ed268_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2416,13825,'','".AddSlashes(pg_result($resaco,0,'ed268_i_codigoinep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2416,13826,'','".AddSlashes(pg_result($resaco,0,'ed268_i_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2416,13827,'','".AddSlashes(pg_result($resaco,0,'ed268_i_calendario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2416,13828,'','".AddSlashes(pg_result($resaco,0,'ed268_c_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2416,13829,'','".AddSlashes(pg_result($resaco,0,'ed268_i_turno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2416,13830,'','".AddSlashes(pg_result($resaco,0,'ed268_i_sala'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2416,13831,'','".AddSlashes(pg_result($resaco,0,'ed268_i_numvagas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2416,13832,'','".AddSlashes(pg_result($resaco,0,'ed268_i_nummatr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2416,13833,'','".AddSlashes(pg_result($resaco,0,'ed268_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2416,13834,'','".AddSlashes(pg_result($resaco,0,'ed268_i_tipoatend'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2416,13835,'','".AddSlashes(pg_result($resaco,0,'ed268_i_ativqtd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2416,14083,'','".AddSlashes(pg_result($resaco,0,'ed268_c_aee'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2416,19954,'','".AddSlashes(pg_result($resaco,0,'ed268_programamaiseducacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");

     }
     return true;
   }
   // funcao para alteracao
   function alterar ($ed268_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update turmaac set ";
     $virgula = "";
     if(trim($this->ed268_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed268_i_codigo"])){
       $sql  .= $virgula." ed268_i_codigo = $this->ed268_i_codigo ";
       $virgula = ",";
       if(trim($this->ed268_i_codigo) == null ){
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed268_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed268_i_codigoinep)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed268_i_codigoinep"])){
        if(trim($this->ed268_i_codigoinep)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed268_i_codigoinep"])){
           $this->ed268_i_codigoinep = "null" ;
        }
       $sql  .= $virgula." ed268_i_codigoinep = $this->ed268_i_codigoinep ";
       $virgula = ",";
     }
     if(trim($this->ed268_i_escola)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed268_i_escola"])){
       $sql  .= $virgula." ed268_i_escola = $this->ed268_i_escola ";
       $virgula = ",";
       if(trim($this->ed268_i_escola) == null ){
         $this->erro_sql = " Campo Escola nao Informado.";
         $this->erro_campo = "ed268_i_escola";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed268_i_calendario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed268_i_calendario"])){
       $sql  .= $virgula." ed268_i_calendario = $this->ed268_i_calendario ";
       $virgula = ",";
       if(trim($this->ed268_i_calendario) == null ){
         $this->erro_sql = " Campo Calendário nao Informado.";
         $this->erro_campo = "ed268_i_calendario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed268_c_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed268_c_descr"])){
       $sql  .= $virgula." ed268_c_descr = '$this->ed268_c_descr' ";
       $virgula = ",";
       if(trim($this->ed268_c_descr) == null ){
         $this->erro_sql = " Campo Nome da Turma nao Informado.";
         $this->erro_campo = "ed268_c_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed268_i_turno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed268_i_turno"])){
       $sql  .= $virgula." ed268_i_turno = $this->ed268_i_turno ";
       $virgula = ",";
       if(trim($this->ed268_i_turno) == null ){
         $this->erro_sql = " Campo Turno nao Informado.";
         $this->erro_campo = "ed268_i_turno";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed268_i_sala)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed268_i_sala"])){
        if(trim($this->ed268_i_sala)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed268_i_sala"])){
           $this->ed268_i_sala = "null" ;
        }
       $sql  .= $virgula." ed268_i_sala = $this->ed268_i_sala ";
       $virgula = ",";
     }
     if(trim($this->ed268_i_numvagas)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed268_i_numvagas"])){
       $sql  .= $virgula." ed268_i_numvagas = $this->ed268_i_numvagas ";
       $virgula = ",";
       if(trim($this->ed268_i_numvagas) == null ){
         $this->erro_sql = " Campo Vagas nao Informado.";
         $this->erro_campo = "ed268_i_numvagas";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed268_i_nummatr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed268_i_nummatr"])){
        if(trim($this->ed268_i_nummatr)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed268_i_nummatr"])){
           $this->ed268_i_nummatr = "0" ;
        }
       $sql  .= $virgula." ed268_i_nummatr = $this->ed268_i_nummatr ";
       $virgula = ",";
     }
     if(trim($this->ed268_t_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed268_t_obs"])){
       $sql  .= $virgula." ed268_t_obs = '$this->ed268_t_obs' ";
       $virgula = ",";
     }
     if(trim($this->ed268_i_tipoatend)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed268_i_tipoatend"])){
       $sql  .= $virgula." ed268_i_tipoatend = $this->ed268_i_tipoatend ";
       $virgula = ",";
       if(trim($this->ed268_i_tipoatend) == null ){
         $this->erro_sql = " Campo Tipo de Atendimento nao Informado.";
         $this->erro_campo = "ed268_i_tipoatend";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed268_i_ativqtd)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed268_i_ativqtd"])){
       $sql  .= $virgula." ed268_i_ativqtd = $this->ed268_i_ativqtd ";
       $virgula = ",";
       if(trim($this->ed268_i_ativqtd) == null ){
         $this->erro_sql = " Campo Qtde. vezes da Atividade / AEE nao Informado.";
         $this->erro_campo = "ed268_i_ativqtd";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed268_c_aee)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed268_c_aee"])){
       $sql  .= $virgula." ed268_c_aee = '$this->ed268_c_aee' ";
       $virgula = ",";
     }
     if(trim($this->ed268_programamaiseducacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed268_programamaiseducacao"])){
        if(trim($this->ed268_programamaiseducacao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed268_programamaiseducacao"])){
           $this->ed268_programamaiseducacao = "null" ;
        }
       $sql  .= $virgula." ed268_programamaiseducacao = $this->ed268_programamaiseducacao ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($ed268_i_codigo!=null){
       $sql .= " ed268_i_codigo = $this->ed268_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed268_i_codigo));
     if ($this->numrows > 0) {

      for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,13824,'$this->ed268_i_codigo','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed268_i_codigo"]) || $this->ed268_i_codigo != "")
             $resac = db_query("insert into db_acount values($acount,2416,13824,'".AddSlashes(pg_result($resaco,$conresaco,'ed268_i_codigo'))."','$this->ed268_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed268_i_codigoinep"]) || $this->ed268_i_codigoinep != "")
             $resac = db_query("insert into db_acount values($acount,2416,13825,'".AddSlashes(pg_result($resaco,$conresaco,'ed268_i_codigoinep'))."','$this->ed268_i_codigoinep',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed268_i_escola"]) || $this->ed268_i_escola != "")
             $resac = db_query("insert into db_acount values($acount,2416,13826,'".AddSlashes(pg_result($resaco,$conresaco,'ed268_i_escola'))."','$this->ed268_i_escola',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed268_i_calendario"]) || $this->ed268_i_calendario != "")
             $resac = db_query("insert into db_acount values($acount,2416,13827,'".AddSlashes(pg_result($resaco,$conresaco,'ed268_i_calendario'))."','$this->ed268_i_calendario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed268_c_descr"]) || $this->ed268_c_descr != "")
             $resac = db_query("insert into db_acount values($acount,2416,13828,'".AddSlashes(pg_result($resaco,$conresaco,'ed268_c_descr'))."','$this->ed268_c_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed268_i_turno"]) || $this->ed268_i_turno != "")
             $resac = db_query("insert into db_acount values($acount,2416,13829,'".AddSlashes(pg_result($resaco,$conresaco,'ed268_i_turno'))."','$this->ed268_i_turno',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed268_i_sala"]) || $this->ed268_i_sala != "")
             $resac = db_query("insert into db_acount values($acount,2416,13830,'".AddSlashes(pg_result($resaco,$conresaco,'ed268_i_sala'))."','$this->ed268_i_sala',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed268_i_numvagas"]) || $this->ed268_i_numvagas != "")
             $resac = db_query("insert into db_acount values($acount,2416,13831,'".AddSlashes(pg_result($resaco,$conresaco,'ed268_i_numvagas'))."','$this->ed268_i_numvagas',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed268_i_nummatr"]) || $this->ed268_i_nummatr != "")
             $resac = db_query("insert into db_acount values($acount,2416,13832,'".AddSlashes(pg_result($resaco,$conresaco,'ed268_i_nummatr'))."','$this->ed268_i_nummatr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed268_t_obs"]) || $this->ed268_t_obs != "")
             $resac = db_query("insert into db_acount values($acount,2416,13833,'".AddSlashes(pg_result($resaco,$conresaco,'ed268_t_obs'))."','$this->ed268_t_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed268_i_tipoatend"]) || $this->ed268_i_tipoatend != "")
             $resac = db_query("insert into db_acount values($acount,2416,13834,'".AddSlashes(pg_result($resaco,$conresaco,'ed268_i_tipoatend'))."','$this->ed268_i_tipoatend',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed268_i_ativqtd"]) || $this->ed268_i_ativqtd != "")
             $resac = db_query("insert into db_acount values($acount,2416,13835,'".AddSlashes(pg_result($resaco,$conresaco,'ed268_i_ativqtd'))."','$this->ed268_i_ativqtd',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed268_c_aee"]) || $this->ed268_c_aee != "")
             $resac = db_query("insert into db_acount values($acount,2416,14083,'".AddSlashes(pg_result($resaco,$conresaco,'ed268_c_aee'))."','$this->ed268_c_aee',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed268_programamaiseducacao"]) || $this->ed268_programamaiseducacao != "")
             $resac = db_query("insert into db_acount values($acount,2416,19954,'".AddSlashes(pg_result($resaco,$conresaco,'ed268_programamaiseducacao'))."','$this->ed268_programamaiseducacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Turma com Atividade Complementar / AEE nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed268_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Turma com Atividade Complementar / AEE nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed268_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed268_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($ed268_i_codigo=null,$dbwhere=null) {

      if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($ed268_i_codigo));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

          $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
          $acount = pg_result($resac,0,0);
          $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
          $resac  = db_query("insert into db_acountkey values($acount,13824,'$ed268_i_codigo','E')");
          $resac  = db_query("insert into db_acount values($acount,2416,13824,'','".AddSlashes(pg_result($resaco,$iresaco,'ed268_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,2416,13825,'','".AddSlashes(pg_result($resaco,$iresaco,'ed268_i_codigoinep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,2416,13826,'','".AddSlashes(pg_result($resaco,$iresaco,'ed268_i_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,2416,13827,'','".AddSlashes(pg_result($resaco,$iresaco,'ed268_i_calendario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,2416,13828,'','".AddSlashes(pg_result($resaco,$iresaco,'ed268_c_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,2416,13829,'','".AddSlashes(pg_result($resaco,$iresaco,'ed268_i_turno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,2416,13830,'','".AddSlashes(pg_result($resaco,$iresaco,'ed268_i_sala'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,2416,13831,'','".AddSlashes(pg_result($resaco,$iresaco,'ed268_i_numvagas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,2416,13832,'','".AddSlashes(pg_result($resaco,$iresaco,'ed268_i_nummatr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,2416,13833,'','".AddSlashes(pg_result($resaco,$iresaco,'ed268_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,2416,13834,'','".AddSlashes(pg_result($resaco,$iresaco,'ed268_i_tipoatend'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,2416,13835,'','".AddSlashes(pg_result($resaco,$iresaco,'ed268_i_ativqtd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,2416,14083,'','".AddSlashes(pg_result($resaco,$iresaco,'ed268_c_aee'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,2416,19954,'','".AddSlashes(pg_result($resaco,$iresaco,'ed268_programamaiseducacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        }
     }
     $sql = " delete from turmaac
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed268_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed268_i_codigo = $ed268_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Turma com Atividade Complementar / AEE nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed268_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Turma com Atividade Complementar / AEE nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed268_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed268_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:turmaac";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
 function sql_query ( $ed268_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from turmaac ";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = turmaac.ed268_i_escola";
     $sql .= "      inner join turno  on  turno.ed15_i_codigo = turmaac.ed268_i_turno";
     $sql .= "      left  join sala  on  sala.ed16_i_codigo = turmaac.ed268_i_sala";
     $sql .= "      inner join calendario  on  calendario.ed52_i_codigo = turmaac.ed268_i_calendario";
     $sql .= "      left join tiposala  on  tiposala.ed14_i_codigo = sala.ed16_i_tiposala";
     $sql .= "      inner join duracaocal  on  duracaocal.ed55_i_codigo = calendario.ed52_i_duracaocal";
     $sql2 = "";
     if($dbwhere==""){
       if($ed268_i_codigo!=null ){
         $sql2 .= " where turmaac.ed268_i_codigo = $ed268_i_codigo ";
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
   function sql_query_file ( $ed268_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from turmaac ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed268_i_codigo!=null ){
         $sql2 .= " where turmaac.ed268_i_codigo = $ed268_i_codigo ";
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
   function sql_query_censo($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

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
    $sSql .= " from turmaac ";
    $sSql .= "      inner join calendario on ed52_i_codigo = ed268_i_calendario ";
    $sSql .= "      inner join escola on ed18_i_codigo = ed268_i_escola ";
    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " turmaac.ed268_i_codigo = $iCodigo ";
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
}
?>