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
//CLASSE DA ENTIDADE matricula
class cl_matricula {
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
   var $ed60_i_codigo = 0;
   var $ed60_i_aluno = 0;
   var $ed60_i_turma = 0;
   var $ed60_i_numaluno = 0;
   var $ed60_c_situacao = null;
   var $ed60_c_concluida = null;
   var $ed60_i_turmaant = 0;
   var $ed60_c_rfanterior = null;
   var $ed60_d_datamatricula_dia = null;
   var $ed60_d_datamatricula_mes = null;
   var $ed60_d_datamatricula_ano = null;
   var $ed60_d_datamatricula = null;
   var $ed60_d_datamodif_dia = null;
   var $ed60_d_datamodif_mes = null;
   var $ed60_d_datamodif_ano = null;
   var $ed60_d_datamodif = null;
   var $ed60_t_obs = null;
   var $ed60_c_ativa = null;
   var $ed60_c_tipo = null;
   var $ed60_c_parecer = null;
   var $ed60_d_datasaida_dia = null;
   var $ed60_d_datasaida_mes = null;
   var $ed60_d_datasaida_ano = null;
   var $ed60_d_datasaida = null;
   var $ed60_d_datamodifant_dia = null;
   var $ed60_d_datamodifant_mes = null;
   var $ed60_d_datamodifant_ano = null;
   var $ed60_d_datamodifant = null;
   var $ed60_matricula = 0;
   var $ed60_tipoingresso = 1;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 ed60_i_codigo = int8 = Matrícula N°
                 ed60_i_aluno = int8 = Aluno
                 ed60_i_turma = int8 = Turma
                 ed60_i_numaluno = int4 = Número
                 ed60_c_situacao = char(20) = Alterar Situação para
                 ed60_c_concluida = char(1) = Concluída
                 ed60_i_turmaant = int8 = Turma Anterior
                 ed60_c_rfanterior = char(1) = R F Anterior
                 ed60_d_datamatricula = date = Data da Matrícula
                 ed60_d_datamodif = date = Data Modificação
                 ed60_t_obs = text = Observações
                 ed60_c_ativa = char(1) = Matrícula Ativa
                 ed60_c_tipo = char(1) = Tipo de Matrícula
                 ed60_c_parecer = char(1) = Avaliação por Parecer
                 ed60_d_datasaida = date = Data Saída
                 ed60_d_datamodifant = date = Data Modificação Anterior
                 ed60_matricula = int4 = Matricula
                 ed60_tipoingresso = int4 = Sequencial
                 ";
   //funcao construtor da classe
   function cl_matricula() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("matricula");
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
       $this->ed60_i_codigo = ($this->ed60_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed60_i_codigo"]:$this->ed60_i_codigo);
       $this->ed60_i_aluno = ($this->ed60_i_aluno == ""?@$GLOBALS["HTTP_POST_VARS"]["ed60_i_aluno"]:$this->ed60_i_aluno);
       $this->ed60_i_turma = ($this->ed60_i_turma == ""?@$GLOBALS["HTTP_POST_VARS"]["ed60_i_turma"]:$this->ed60_i_turma);
       $this->ed60_i_numaluno = ($this->ed60_i_numaluno == ""?@$GLOBALS["HTTP_POST_VARS"]["ed60_i_numaluno"]:$this->ed60_i_numaluno);
       $this->ed60_c_situacao = ($this->ed60_c_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed60_c_situacao"]:$this->ed60_c_situacao);
       $this->ed60_c_concluida = ($this->ed60_c_concluida == ""?@$GLOBALS["HTTP_POST_VARS"]["ed60_c_concluida"]:$this->ed60_c_concluida);
       $this->ed60_i_turmaant = ($this->ed60_i_turmaant == ""?@$GLOBALS["HTTP_POST_VARS"]["ed60_i_turmaant"]:$this->ed60_i_turmaant);
       $this->ed60_c_rfanterior = ($this->ed60_c_rfanterior == ""?@$GLOBALS["HTTP_POST_VARS"]["ed60_c_rfanterior"]:$this->ed60_c_rfanterior);
       if($this->ed60_d_datamatricula == ""){
         $this->ed60_d_datamatricula_dia = ($this->ed60_d_datamatricula_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed60_d_datamatricula_dia"]:$this->ed60_d_datamatricula_dia);
         $this->ed60_d_datamatricula_mes = ($this->ed60_d_datamatricula_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed60_d_datamatricula_mes"]:$this->ed60_d_datamatricula_mes);
         $this->ed60_d_datamatricula_ano = ($this->ed60_d_datamatricula_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed60_d_datamatricula_ano"]:$this->ed60_d_datamatricula_ano);
         if($this->ed60_d_datamatricula_dia != ""){
            $this->ed60_d_datamatricula = $this->ed60_d_datamatricula_ano."-".$this->ed60_d_datamatricula_mes."-".$this->ed60_d_datamatricula_dia;
         }
       }
       if($this->ed60_d_datamodif == ""){
         $this->ed60_d_datamodif_dia = ($this->ed60_d_datamodif_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed60_d_datamodif_dia"]:$this->ed60_d_datamodif_dia);
         $this->ed60_d_datamodif_mes = ($this->ed60_d_datamodif_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed60_d_datamodif_mes"]:$this->ed60_d_datamodif_mes);
         $this->ed60_d_datamodif_ano = ($this->ed60_d_datamodif_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed60_d_datamodif_ano"]:$this->ed60_d_datamodif_ano);
         if($this->ed60_d_datamodif_dia != ""){
            $this->ed60_d_datamodif = $this->ed60_d_datamodif_ano."-".$this->ed60_d_datamodif_mes."-".$this->ed60_d_datamodif_dia;
         }
       }
       $this->ed60_t_obs = ($this->ed60_t_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["ed60_t_obs"]:$this->ed60_t_obs);
       $this->ed60_c_ativa = ($this->ed60_c_ativa == ""?@$GLOBALS["HTTP_POST_VARS"]["ed60_c_ativa"]:$this->ed60_c_ativa);
       $this->ed60_c_tipo = ($this->ed60_c_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed60_c_tipo"]:$this->ed60_c_tipo);
       $this->ed60_c_parecer = ($this->ed60_c_parecer == ""?@$GLOBALS["HTTP_POST_VARS"]["ed60_c_parecer"]:$this->ed60_c_parecer);
       if($this->ed60_d_datasaida == ""){
         $this->ed60_d_datasaida_dia = ($this->ed60_d_datasaida_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed60_d_datasaida_dia"]:$this->ed60_d_datasaida_dia);
         $this->ed60_d_datasaida_mes = ($this->ed60_d_datasaida_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed60_d_datasaida_mes"]:$this->ed60_d_datasaida_mes);
         $this->ed60_d_datasaida_ano = ($this->ed60_d_datasaida_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed60_d_datasaida_ano"]:$this->ed60_d_datasaida_ano);
         if($this->ed60_d_datasaida_dia != ""){
            $this->ed60_d_datasaida = $this->ed60_d_datasaida_ano."-".$this->ed60_d_datasaida_mes."-".$this->ed60_d_datasaida_dia;
         }
       }
       if($this->ed60_d_datamodifant == ""){
         $this->ed60_d_datamodifant_dia = ($this->ed60_d_datamodifant_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed60_d_datamodifant_dia"]:$this->ed60_d_datamodifant_dia);
         $this->ed60_d_datamodifant_mes = ($this->ed60_d_datamodifant_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed60_d_datamodifant_mes"]:$this->ed60_d_datamodifant_mes);
         $this->ed60_d_datamodifant_ano = ($this->ed60_d_datamodifant_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed60_d_datamodifant_ano"]:$this->ed60_d_datamodifant_ano);
         if($this->ed60_d_datamodifant_dia != ""){
            $this->ed60_d_datamodifant = $this->ed60_d_datamodifant_ano."-".$this->ed60_d_datamodifant_mes."-".$this->ed60_d_datamodifant_dia;
         }
       }
       $this->ed60_matricula = ($this->ed60_matricula == ""?@$GLOBALS["HTTP_POST_VARS"]["ed60_matricula"]:$this->ed60_matricula);
       $this->ed60_tipoingresso = ($this->ed60_tipoingresso == ""?@$GLOBALS["HTTP_POST_VARS"]["ed60_tipoingresso"]:$this->ed60_tipoingresso);
     }else{
       $this->ed60_i_codigo = ($this->ed60_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed60_i_codigo"]:$this->ed60_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed60_i_codigo){
      $this->atualizacampos();
     if($this->ed60_i_aluno == null ){
       $this->erro_sql = " Campo Aluno não informado.";
       $this->erro_campo = "ed60_i_aluno";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed60_i_turma == null ){
       $this->erro_sql = " Campo Turma não informado.";
       $this->erro_campo = "ed60_i_turma";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed60_i_numaluno == null ){
       $this->ed60_i_numaluno = "null";
     }
     if($this->ed60_c_situacao == null ){
       $this->erro_sql = " Campo Alterar Situação para não informado.";
       $this->erro_campo = "ed60_c_situacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed60_c_concluida == null ){
       $this->erro_sql = " Campo Concluída não informado.";
       $this->erro_campo = "ed60_c_concluida";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed60_i_turmaant == null ){
       $this->ed60_i_turmaant = "null";
     }
     if($this->ed60_d_datamatricula == null ){
       $this->erro_sql = " Campo Data da Matrícula não informado.";
       $this->erro_campo = "ed60_d_datamatricula_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed60_d_datamodif == null ){
       $this->erro_sql = " Campo Data Modificação não informado.";
       $this->erro_campo = "ed60_d_datamodif_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed60_c_ativa == null ){
       $this->erro_sql = " Campo Matrícula Ativa não informado.";
       $this->erro_campo = "ed60_c_ativa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed60_c_tipo == null ){
       $this->erro_sql = " Campo Tipo de Matrícula não informado.";
       $this->erro_campo = "ed60_c_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed60_c_parecer == null ){
       $this->erro_sql = " Campo Avaliação por Parecer não informado.";
       $this->erro_campo = "ed60_c_parecer";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed60_d_datasaida == null ){
       $this->ed60_d_datasaida = "null";
     }
     if($this->ed60_d_datamodifant == null ){
       $this->ed60_d_datamodifant = "null";
     }
     if($ed60_i_codigo == "" || $ed60_i_codigo == null ){
       $result = db_query("select nextval('matricula_ed60_i_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: matricula_ed60_i_codigo_seq do campo: ed60_i_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->ed60_i_codigo = pg_result($result,0,0);

       if (empty($this->ed60_matricula)) {
         $this->ed60_matricula = $this->ed60_i_codigo;
       }
     }else{
       $result = db_query("select last_value from matricula_ed60_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed60_i_codigo)){
         $this->erro_sql = " Campo ed60_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed60_i_codigo = $ed60_i_codigo;
       }
     }
     if(($this->ed60_i_codigo == null) || ($this->ed60_i_codigo == "") ){
       $this->erro_sql = " Campo ed60_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into matricula(
                                       ed60_i_codigo
                                      ,ed60_i_aluno
                                      ,ed60_i_turma
                                      ,ed60_i_numaluno
                                      ,ed60_c_situacao
                                      ,ed60_c_concluida
                                      ,ed60_i_turmaant
                                      ,ed60_c_rfanterior
                                      ,ed60_d_datamatricula
                                      ,ed60_d_datamodif
                                      ,ed60_t_obs
                                      ,ed60_c_ativa
                                      ,ed60_c_tipo
                                      ,ed60_c_parecer
                                      ,ed60_d_datasaida
                                      ,ed60_d_datamodifant
                                      ,ed60_matricula
                                      ,ed60_tipoingresso
                       )
                values (
                                $this->ed60_i_codigo
                               ,$this->ed60_i_aluno
                               ,$this->ed60_i_turma
                               ,$this->ed60_i_numaluno
                               ,'$this->ed60_c_situacao'
                               ,'$this->ed60_c_concluida'
                               ,$this->ed60_i_turmaant
                               ,'$this->ed60_c_rfanterior'
                               ,".($this->ed60_d_datamatricula == "null" || $this->ed60_d_datamatricula == ""?"null":"'".$this->ed60_d_datamatricula."'")."
                               ,".($this->ed60_d_datamodif == "null" || $this->ed60_d_datamodif == ""?"null":"'".$this->ed60_d_datamodif."'")."
                               ,'$this->ed60_t_obs'
                               ,'$this->ed60_c_ativa'
                               ,'$this->ed60_c_tipo'
                               ,'$this->ed60_c_parecer'
                               ,".($this->ed60_d_datasaida == "null" || $this->ed60_d_datasaida == ""?"null":"'".$this->ed60_d_datasaida."'")."
                               ,".($this->ed60_d_datamodifant == "null" || $this->ed60_d_datamodifant == ""?"null":"'".$this->ed60_d_datamodifant."'")."
                               ,$this->ed60_matricula
                               ,$this->ed60_tipoingresso
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Matrícula do aluno na Turma ($this->ed60_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Matrícula do aluno na Turma já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Matrícula do aluno na Turma ($this->ed60_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed60_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed60_i_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008618,'$this->ed60_i_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,1010112,1008618,'','".AddSlashes(pg_result($resaco,0,'ed60_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010112,1008619,'','".AddSlashes(pg_result($resaco,0,'ed60_i_aluno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010112,1008620,'','".AddSlashes(pg_result($resaco,0,'ed60_i_turma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010112,1008621,'','".AddSlashes(pg_result($resaco,0,'ed60_i_numaluno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010112,1008622,'','".AddSlashes(pg_result($resaco,0,'ed60_c_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010112,1008623,'','".AddSlashes(pg_result($resaco,0,'ed60_c_concluida'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010112,1008624,'','".AddSlashes(pg_result($resaco,0,'ed60_i_turmaant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010112,1008625,'','".AddSlashes(pg_result($resaco,0,'ed60_c_rfanterior'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010112,1008626,'','".AddSlashes(pg_result($resaco,0,'ed60_d_datamatricula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010112,1008627,'','".AddSlashes(pg_result($resaco,0,'ed60_d_datamodif'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010112,1008651,'','".AddSlashes(pg_result($resaco,0,'ed60_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010112,12350,'','".AddSlashes(pg_result($resaco,0,'ed60_c_ativa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010112,12351,'','".AddSlashes(pg_result($resaco,0,'ed60_c_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010112,12611,'','".AddSlashes(pg_result($resaco,0,'ed60_c_parecer'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010112,14549,'','".AddSlashes(pg_result($resaco,0,'ed60_d_datasaida'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010112,14550,'','".AddSlashes(pg_result($resaco,0,'ed60_d_datamodifant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010112,19290,'','".AddSlashes(pg_result($resaco,0,'ed60_matricula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010112,20362,'','".AddSlashes(pg_result($resaco,0,'ed60_tipoingresso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     return true;
   }
   // funcao para alteracao
   function alterar ($ed60_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update matricula set ";
     $virgula = "";
     if(trim($this->ed60_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed60_i_codigo"])){
       $sql  .= $virgula." ed60_i_codigo = $this->ed60_i_codigo ";
       $virgula = ",";
       if(trim($this->ed60_i_codigo) == null ){
         $this->erro_sql = " Campo Matrícula N° não informado.";
         $this->erro_campo = "ed60_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed60_i_aluno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed60_i_aluno"])){
       $sql  .= $virgula." ed60_i_aluno = $this->ed60_i_aluno ";
       $virgula = ",";
       if(trim($this->ed60_i_aluno) == null ){
         $this->erro_sql = " Campo Aluno não informado.";
         $this->erro_campo = "ed60_i_aluno";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed60_i_turma)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed60_i_turma"])){
       $sql  .= $virgula." ed60_i_turma = $this->ed60_i_turma ";
       $virgula = ",";
       if(trim($this->ed60_i_turma) == null ){
         $this->erro_sql = " Campo Turma não informado.";
         $this->erro_campo = "ed60_i_turma";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed60_i_numaluno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed60_i_numaluno"])){
        if(trim($this->ed60_i_numaluno)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed60_i_numaluno"])){
           $this->ed60_i_numaluno = "null" ;
        }
       $sql  .= $virgula." ed60_i_numaluno = $this->ed60_i_numaluno ";
       $virgula = ",";
     }
     if(trim($this->ed60_c_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed60_c_situacao"])){
       $sql  .= $virgula." ed60_c_situacao = '$this->ed60_c_situacao' ";
       $virgula = ",";
       if(trim($this->ed60_c_situacao) == null ){
         $this->erro_sql = " Campo Alterar Situação para não informado.";
         $this->erro_campo = "ed60_c_situacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed60_c_concluida)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed60_c_concluida"])){
       $sql  .= $virgula." ed60_c_concluida = '$this->ed60_c_concluida' ";
       $virgula = ",";
       if(trim($this->ed60_c_concluida) == null ){
         $this->erro_sql = " Campo Concluída não informado.";
         $this->erro_campo = "ed60_c_concluida";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed60_i_turmaant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed60_i_turmaant"])){
        if(trim($this->ed60_i_turmaant)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed60_i_turmaant"])){
           $this->ed60_i_turmaant = "null" ;
        }
       $sql  .= $virgula." ed60_i_turmaant = $this->ed60_i_turmaant ";
       $virgula = ",";
     }
     if(trim($this->ed60_c_rfanterior)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed60_c_rfanterior"])){
       $sql  .= $virgula." ed60_c_rfanterior = '$this->ed60_c_rfanterior' ";
       $virgula = ",";
     }
     if(trim($this->ed60_d_datamatricula)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed60_d_datamatricula_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed60_d_datamatricula_dia"] !="") ){
       $sql  .= $virgula." ed60_d_datamatricula = '$this->ed60_d_datamatricula' ";
       $virgula = ",";
       if(trim($this->ed60_d_datamatricula) == null ){
         $this->erro_sql = " Campo Data da Matrícula não informado.";
         $this->erro_campo = "ed60_d_datamatricula_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed60_d_datamatricula_dia"])){
         $sql  .= $virgula." ed60_d_datamatricula = null ";
         $virgula = ",";
         if(trim($this->ed60_d_datamatricula) == null ){
           $this->erro_sql = " Campo Data da Matrícula não informado.";
           $this->erro_campo = "ed60_d_datamatricula_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ed60_d_datamodif)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed60_d_datamodif_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed60_d_datamodif_dia"] !="") ){
       $sql  .= $virgula." ed60_d_datamodif = '$this->ed60_d_datamodif' ";
       $virgula = ",";
       if(trim($this->ed60_d_datamodif) == null ){
         $this->erro_sql = " Campo Data Modificação não informado.";
         $this->erro_campo = "ed60_d_datamodif_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed60_d_datamodif_dia"])){
         $sql  .= $virgula." ed60_d_datamodif = null ";
         $virgula = ",";
         if(trim($this->ed60_d_datamodif) == null ){
           $this->erro_sql = " Campo Data Modificação não informado.";
           $this->erro_campo = "ed60_d_datamodif_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ed60_t_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed60_t_obs"])){
       $sql  .= $virgula." ed60_t_obs = '$this->ed60_t_obs' ";
       $virgula = ",";
     }
     if(trim($this->ed60_c_ativa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed60_c_ativa"])){
       $sql  .= $virgula." ed60_c_ativa = '$this->ed60_c_ativa' ";
       $virgula = ",";
       if(trim($this->ed60_c_ativa) == null ){
         $this->erro_sql = " Campo Matrícula Ativa não informado.";
         $this->erro_campo = "ed60_c_ativa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed60_c_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed60_c_tipo"])){
       $sql  .= $virgula." ed60_c_tipo = '$this->ed60_c_tipo' ";
       $virgula = ",";
       if(trim($this->ed60_c_tipo) == null ){
         $this->erro_sql = " Campo Tipo de Matrícula não informado.";
         $this->erro_campo = "ed60_c_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed60_c_parecer)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed60_c_parecer"])){
       $sql  .= $virgula." ed60_c_parecer = '$this->ed60_c_parecer' ";
       $virgula = ",";
       if(trim($this->ed60_c_parecer) == null ){
         $this->erro_sql = " Campo Avaliação por Parecer não informado.";
         $this->erro_campo = "ed60_c_parecer";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
   if (   trim($this->ed60_d_datasaida) != ""
         || $this->ed60_d_datasaida       == "null"
         || isset($GLOBALS["HTTP_POST_VARS"]["ed60_d_datasaida_dia"])
         && ($GLOBALS["HTTP_POST_VARS"]["ed60_d_datasaida_dia"] != "") ) {

       if ($this->ed60_d_datasaida == "null" || $this->ed60_d_datasaida == null) {
         $sql     .= $virgula." ed60_d_datasaida = null ";
       } else {
         $sql     .= $virgula." ed60_d_datasaida = '$this->ed60_d_datasaida' ";
       }
       $virgula  = ",";

     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed60_d_datasaida_dia"])){
         $sql  .= $virgula." ed60_d_datasaida = null ";
         $virgula = ",";
       }
     }
     if(trim($this->ed60_d_datamodifant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed60_d_datamodifant_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed60_d_datamodifant_dia"] !="") ){
       $sql  .= $virgula." ed60_d_datamodifant = '$this->ed60_d_datamodifant' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed60_d_datamodifant_dia"])){
         $sql  .= $virgula." ed60_d_datamodifant = null ";
         $virgula = ",";
       }
     }
     if(trim($this->ed60_matricula)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed60_matricula"])){
       $sql  .= $virgula." ed60_matricula = $this->ed60_matricula ";
       $virgula = ",";
       if(trim($this->ed60_matricula) == null ){
         $this->erro_sql = " Campo Matricula não informado.";
         $this->erro_campo = "ed60_matricula";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed60_tipoingresso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed60_tipoingresso"])){
       $sql  .= $virgula." ed60_tipoingresso = $this->ed60_tipoingresso ";
       $virgula = ",";
       if(trim($this->ed60_tipoingresso) == null ){
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "ed60_tipoingresso";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed60_i_codigo!=null){
       $sql .= " ed60_i_codigo = $this->ed60_i_codigo";
     }

       $resaco = $this->sql_record($this->sql_query_file($this->ed60_i_codigo));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1008618,'$this->ed60_i_codigo','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed60_i_codigo"]) || $this->ed60_i_codigo != "")
             $resac = db_query("insert into db_acount values($acount,1010112,1008618,'".AddSlashes(pg_result($resaco,$conresaco,'ed60_i_codigo'))."','$this->ed60_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed60_i_aluno"]) || $this->ed60_i_aluno != "")
             $resac = db_query("insert into db_acount values($acount,1010112,1008619,'".AddSlashes(pg_result($resaco,$conresaco,'ed60_i_aluno'))."','$this->ed60_i_aluno',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed60_i_turma"]) || $this->ed60_i_turma != "")
             $resac = db_query("insert into db_acount values($acount,1010112,1008620,'".AddSlashes(pg_result($resaco,$conresaco,'ed60_i_turma'))."','$this->ed60_i_turma',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed60_i_numaluno"]) || $this->ed60_i_numaluno != "")
             $resac = db_query("insert into db_acount values($acount,1010112,1008621,'".AddSlashes(pg_result($resaco,$conresaco,'ed60_i_numaluno'))."','$this->ed60_i_numaluno',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed60_c_situacao"]) || $this->ed60_c_situacao != "")
             $resac = db_query("insert into db_acount values($acount,1010112,1008622,'".AddSlashes(pg_result($resaco,$conresaco,'ed60_c_situacao'))."','$this->ed60_c_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed60_c_concluida"]) || $this->ed60_c_concluida != "")
             $resac = db_query("insert into db_acount values($acount,1010112,1008623,'".AddSlashes(pg_result($resaco,$conresaco,'ed60_c_concluida'))."','$this->ed60_c_concluida',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed60_i_turmaant"]) || $this->ed60_i_turmaant != "")
             $resac = db_query("insert into db_acount values($acount,1010112,1008624,'".AddSlashes(pg_result($resaco,$conresaco,'ed60_i_turmaant'))."','$this->ed60_i_turmaant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed60_c_rfanterior"]) || $this->ed60_c_rfanterior != "")
             $resac = db_query("insert into db_acount values($acount,1010112,1008625,'".AddSlashes(pg_result($resaco,$conresaco,'ed60_c_rfanterior'))."','$this->ed60_c_rfanterior',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed60_d_datamatricula"]) || $this->ed60_d_datamatricula != "")
             $resac = db_query("insert into db_acount values($acount,1010112,1008626,'".AddSlashes(pg_result($resaco,$conresaco,'ed60_d_datamatricula'))."','$this->ed60_d_datamatricula',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed60_d_datamodif"]) || $this->ed60_d_datamodif != "")
             $resac = db_query("insert into db_acount values($acount,1010112,1008627,'".AddSlashes(pg_result($resaco,$conresaco,'ed60_d_datamodif'))."','$this->ed60_d_datamodif',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed60_t_obs"]) || $this->ed60_t_obs != "")
             $resac = db_query("insert into db_acount values($acount,1010112,1008651,'".AddSlashes(pg_result($resaco,$conresaco,'ed60_t_obs'))."','$this->ed60_t_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed60_c_ativa"]) || $this->ed60_c_ativa != "")
             $resac = db_query("insert into db_acount values($acount,1010112,12350,'".AddSlashes(pg_result($resaco,$conresaco,'ed60_c_ativa'))."','$this->ed60_c_ativa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed60_c_tipo"]) || $this->ed60_c_tipo != "")
             $resac = db_query("insert into db_acount values($acount,1010112,12351,'".AddSlashes(pg_result($resaco,$conresaco,'ed60_c_tipo'))."','$this->ed60_c_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed60_c_parecer"]) || $this->ed60_c_parecer != "")
             $resac = db_query("insert into db_acount values($acount,1010112,12611,'".AddSlashes(pg_result($resaco,$conresaco,'ed60_c_parecer'))."','$this->ed60_c_parecer',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed60_d_datasaida"]) || $this->ed60_d_datasaida != "")
             $resac = db_query("insert into db_acount values($acount,1010112,14549,'".AddSlashes(pg_result($resaco,$conresaco,'ed60_d_datasaida'))."','$this->ed60_d_datasaida',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed60_d_datamodifant"]) || $this->ed60_d_datamodifant != "")
             $resac = db_query("insert into db_acount values($acount,1010112,14550,'".AddSlashes(pg_result($resaco,$conresaco,'ed60_d_datamodifant'))."','$this->ed60_d_datamodifant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed60_matricula"]) || $this->ed60_matricula != "")
             $resac = db_query("insert into db_acount values($acount,1010112,19290,'".AddSlashes(pg_result($resaco,$conresaco,'ed60_matricula'))."','$this->ed60_matricula',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed60_tipoingresso"]) || $this->ed60_tipoingresso != "")
             $resac = db_query("insert into db_acount values($acount,1010112,20362,'".AddSlashes(pg_result($resaco,$conresaco,'ed60_tipoingresso'))."','$this->ed60_tipoingresso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");

       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Matrícula do aluno na Turma nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed60_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Matrícula do aluno na Turma nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed60_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed60_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($ed60_i_codigo=null,$dbwhere=null) {
       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($ed60_i_codigo));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1008618,'$ed60_i_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,1010112,1008618,'','".AddSlashes(pg_result($resaco,$iresaco,'ed60_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010112,1008619,'','".AddSlashes(pg_result($resaco,$iresaco,'ed60_i_aluno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010112,1008620,'','".AddSlashes(pg_result($resaco,$iresaco,'ed60_i_turma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010112,1008621,'','".AddSlashes(pg_result($resaco,$iresaco,'ed60_i_numaluno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010112,1008622,'','".AddSlashes(pg_result($resaco,$iresaco,'ed60_c_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010112,1008623,'','".AddSlashes(pg_result($resaco,$iresaco,'ed60_c_concluida'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010112,1008624,'','".AddSlashes(pg_result($resaco,$iresaco,'ed60_i_turmaant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010112,1008625,'','".AddSlashes(pg_result($resaco,$iresaco,'ed60_c_rfanterior'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010112,1008626,'','".AddSlashes(pg_result($resaco,$iresaco,'ed60_d_datamatricula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010112,1008627,'','".AddSlashes(pg_result($resaco,$iresaco,'ed60_d_datamodif'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010112,1008651,'','".AddSlashes(pg_result($resaco,$iresaco,'ed60_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010112,12350,'','".AddSlashes(pg_result($resaco,$iresaco,'ed60_c_ativa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010112,12351,'','".AddSlashes(pg_result($resaco,$iresaco,'ed60_c_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010112,12611,'','".AddSlashes(pg_result($resaco,$iresaco,'ed60_c_parecer'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010112,14549,'','".AddSlashes(pg_result($resaco,$iresaco,'ed60_d_datasaida'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010112,14550,'','".AddSlashes(pg_result($resaco,$iresaco,'ed60_d_datamodifant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010112,19290,'','".AddSlashes(pg_result($resaco,$iresaco,'ed60_matricula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010112,20362,'','".AddSlashes(pg_result($resaco,$iresaco,'ed60_tipoingresso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }

     $sql = " delete from matricula
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed60_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed60_i_codigo = $ed60_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Matrícula do aluno na Turma nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed60_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Matrícula do aluno na Turma nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed60_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed60_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:matricula";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $ed60_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from matricula ";
     $sql .= "      inner join aluno  on  aluno.ed47_i_codigo = matricula.ed60_i_aluno";
     $sql .= "      left join pais   on pais.ed228_i_codigo = aluno.ed47_i_pais";
     $sql .= "      inner join turma  on  turma.ed57_i_codigo = matricula.ed60_i_turma";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = turma.ed57_i_escola";
     $sql .= "      left join censouf  on  censouf.ed260_i_codigo = escola.ed18_i_censouf";
     $sql .= "      left join censomunic  on  censomunic.ed261_i_codigo = escola.ed18_i_censomunic";
     $sql .= "      inner join turno  on  turno.ed15_i_codigo = turma.ed57_i_turno";
     $sql .= "      inner join sala  on  sala.ed16_i_codigo = turma.ed57_i_sala";
     $sql .= "      inner join calendario  on  calendario.ed52_i_codigo = turma.ed57_i_calendario";
     $sql .= "      inner join base  on  base.ed31_i_codigo = turma.ed57_i_base";
     $sql .= "      inner join cursoedu  on  cursoedu.ed29_i_codigo = base.ed31_i_curso";
     $sql .= "      inner join ensino  on  ensino.ed10_i_codigo = cursoedu.ed29_i_ensino";
     $sql .= "      inner join matriculaserie  on  matriculaserie.ed221_i_matricula = matricula.ed60_i_codigo";
     $sql .= "      inner join serie  on  serie.ed11_i_codigo = matriculaserie.ed221_i_serie";
     $sql .= "      inner join serieregimemat  on  serieregimemat.ed223_i_serie = serie.ed11_i_codigo";
     $sql .= "      inner join turmaserieregimemat  on  turmaserieregimemat.ed220_i_serieregimemat = serieregimemat.ed223_i_codigo";
     $sql .= "                                      and turmaserieregimemat.ed220_i_turma = matricula.ed60_i_turma";
     $sql .= "      inner join procedimento  on  procedimento.ed40_i_codigo = turmaserieregimemat.ed220_i_procedimento";
     $sql .= "      left join turma as turmaant on  turmaant.ed57_i_codigo = matricula.ed60_i_turmaant";
     $sql .= "      left join escola  as escolaant on  escolaant.ed18_i_codigo = turmaant.ed57_i_escola";
     $sql .= "      left join turno  as turnoant on  turnoant.ed15_i_codigo = turmaant.ed57_i_turno";
     $sql .= "      left join sala  as salaant on  salaant.ed16_i_codigo = turmaant.ed57_i_sala";
     $sql .= "      left join calendario  as calendarioant on  calendarioant.ed52_i_codigo = turmaant.ed57_i_calendario";
     $sql .= "      left join base  as baseant on  baseant.ed31_i_codigo = turmaant.ed57_i_base";
     $sql .= "      left join alunoprimat  on  alunoprimat.ed76_i_aluno = aluno.ed47_i_codigo";
     $sql .= "      left join escola  as escolaprimat on  escolaprimat.ed18_i_codigo = alunoprimat.ed76_i_escola";
     $sql .= "      left join escolaproc  on  escolaproc.ed82_i_codigo = alunoprimat.ed76_i_escola";
     $sql2 = "";
     if($dbwhere==""){
       if($ed60_i_codigo!=null ){
         $sql2 .= " where matriculaserie.ed221_c_origem = 'S' AND matricula.ed60_i_codigo = $ed60_i_codigo ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where matriculaserie.ed221_c_origem = 'S' AND $dbwhere";
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
   function sql_query_file ( $ed60_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from matricula ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed60_i_codigo!=null ){
         $sql2 .= " where matricula.ed60_i_codigo = $ed60_i_codigo ";
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
  function sql_query_frequencia($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

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
    $sSql .= " from matricula " ;
    $sSql .= "      inner join aluno on ed47_i_codigo = ed60_i_aluno ";
    $sSql .= "      inner join diario on ed95_i_aluno = ed47_i_codigo ";
    $sSql .= "      inner join diarioavaliacao on ed72_i_diario = ed95_i_codigo ";
    $sSql .= "      inner join regencia on ed59_i_codigo = ed95_i_regencia  ";
    $sSql .= "      inner join regenciaperiodo on ed78_i_regencia = ed59_i_codigo and ed78_i_procavaliacao = ed72_i_procavaliacao ";
    $sSql .= "      inner join disciplina on ed12_i_codigo = ed59_i_disciplina  ";
    $sSql .= "      inner join caddisciplina on ed232_i_codigo= ed12_i_caddisciplina ";
    $sSql .= "      inner join procavaliacao on ed41_i_codigo = ed72_i_procavaliacao ";
    $sSql .= "      inner join periodoavaliacao on ed09_i_codigo = ed41_i_periodoavaliacao ";
    $sSql .= "      left join abonofalta on ed80_i_diarioavaliacao = ed72_i_codigo  ";
    $sSql .= "      inner join matriculaserie on ed221_i_matricula = ed60_i_codigo ";
    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where matriculaserie.ed221_c_origem = 'S' AND matricula.ed60_i_codigo = $iCodigo ";
      }

    } elseif ($sDbWhere != '') {
    $sSql2 = " where matriculaserie.ed221_c_origem = 'S' AND $sDbWhere";
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
   function sql_query_restricao ( $ed60_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from matricula ";
     $sql .= "      inner join aluno  on  aluno.ed47_i_codigo = matricula.ed60_i_aluno";
     $sql .= "      inner join turma  on  turma.ed57_i_codigo = matricula.ed60_i_turma";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = turma.ed57_i_escola";
     $sql .= "      inner join censouf  on  censouf.ed260_i_codigo = escola.ed18_i_censouf";
     $sql .= "      inner join censomunic  on  censomunic.ed261_i_codigo = escola.ed18_i_censomunic";
     $sql .= "      inner join turno  on  turno.ed15_i_codigo = turma.ed57_i_turno";
     $sql .= "      inner join sala  on  sala.ed16_i_codigo = turma.ed57_i_sala";
     $sql .= "      inner join calendario  on  calendario.ed52_i_codigo = turma.ed57_i_calendario";
     $sql .= "      inner join base  on  base.ed31_i_codigo = turma.ed57_i_base";
     $sql .= "      inner join cursoedu  on  cursoedu.ed29_i_codigo = base.ed31_i_curso";
     $sql .= "      inner join ensino  on  ensino.ed10_i_codigo = cursoedu.ed29_i_ensino";
     $sql .= "      inner join procedimento  on  procedimento.ed40_i_codigo = turma.ed57_i_procedimento";
     $sql .= "      inner join matriculaserie  on  matriculaserie.ed221_i_matricula = matricula.ed60_i_codigo";
     $sql .= "      inner join serie  on  serie.ed11_i_codigo = matriculaserie.ed221_i_serie";
     $sql .= "      left join turma as turmaant on  turmaant.ed57_i_codigo = matricula.ed60_i_turmaant";
     $sql .= "      left join escola  as escolaant on  escolaant.ed18_i_codigo = turmaant.ed57_i_escola";
     $sql .= "      left join turno  as turnoant on  turnoant.ed15_i_codigo = turmaant.ed57_i_turno";
     $sql .= "      left join sala  as salaant on  salaant.ed16_i_codigo = turmaant.ed57_i_sala";
     $sql .= "      left join calendario  as calendarioant on  calendarioant.ed52_i_codigo = turmaant.ed57_i_calendario";
     $sql .= "      left join base  as baseant on  baseant.ed31_i_codigo = turmaant.ed57_i_base";
     $sql .= "      left join procedimento  as procedimentoant on  procedimentoant.ed40_i_codigo = turmaant.ed57_i_procedimento";
     $sql .= "      left join alunoprimat  on  alunoprimat.ed76_i_aluno = aluno.ed47_i_codigo";
     $sql .= "      left join escola  as escolaprimat on  escolaprimat.ed18_i_codigo = alunoprimat.ed76_i_escola";
     $sql .= "      left join escolaproc  on  escolaproc.ed82_i_codigo = alunoprimat.ed76_i_escola";
     $sql .= "      inner join mer_restricao  on  mer_restricao.me24_i_aluno = aluno.ed47_i_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($ed60_i_codigo!=null ){
         $sql2 .= " where matriculaserie.ed221_c_origem = 'S' AND matricula.ed60_i_codigo = $ed60_i_codigo ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where matriculaserie.ed221_c_origem = 'S' AND $dbwhere";
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
   function sql_query_diario ( $ed60_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from matricula ";
     $sql .= "      inner join aluno  on  aluno.ed47_i_codigo = matricula.ed60_i_aluno";
     $sql .= "      left join alunoprimat  on  alunoprimat.ed76_i_aluno = aluno.ed47_i_codigo";
     $sql .= "      left join escola  on  escola.ed18_i_codigo = alunoprimat.ed76_i_escola";
     $sql .= "      left join censouf  on  censouf.ed260_i_codigo = escola.ed18_i_censouf";
     $sql .= "      left join censomunic  on  censomunic.ed261_i_codigo = escola.ed18_i_censomunic";
     $sql .= "      left join escolaproc  on  escolaproc.ed82_i_codigo = alunoprimat.ed76_i_escola";
     $sql .= "      inner join turma  on  turma.ed57_i_codigo = matricula.ed60_i_turma";
     $sql .= "      inner join matriculaserie  on  matriculaserie.ed221_i_matricula = matricula.ed60_i_codigo";
     $sql .= "      inner join serie  on  serie.ed11_i_codigo = matriculaserie.ed221_i_serie";
     $sql2 = "";

     if($dbwhere==""){
       if($ed60_i_codigo!=null ){
         $sql2 .= " where matriculaserie.ed221_c_origem = 'S' AND matricula.ed60_i_codigo = $ed60_i_codigo ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where matriculaserie.ed221_c_origem = 'S' AND $dbwhere";
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
   function sql_query_bolsafamilia($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

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
    $sSql .= " from matricula " ;
    $sSql .= "      inner join turma on ed57_i_codigo = ed60_i_turma ";
    $sSql .= "      inner join escola on ed18_i_codigo = ed57_i_escola ";
    $sSql .= "      inner join calendario on ed52_i_codigo = ed57_i_calendario ";
    $sSql .= "      inner join matriculaserie on ed221_i_matricula = ed60_i_codigo ";
    $sSql .= "      inner join serie on ed11_i_codigo = ed221_i_serie ";
    $sSql .= "      inner join aluno on ed47_i_codigo = ed60_i_aluno ";
    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where matriculaserie.ed221_c_origem = 'S' AND matricula.ed60_i_codigo = $iCodigo ";
      }

    } elseif ($sDbWhere != '') {
      $sSql2 = " where matriculaserie.ed221_c_origem = 'S' AND $sDbWhere";
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
  function sql_query_transferenciarede($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

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
    $sSql .= " from matricula " ;
    $sSql .= "      inner join turma on ed57_i_codigo = ed60_i_turma ";
    $sSql .= "      inner join matriculaserie on ed221_i_matricula = ed60_i_codigo ";
    $sSql .= "      inner join serie on ed11_i_codigo = ed221_i_serie ";
    $sSql .= "      inner join base on ed31_i_codigo = ed57_i_base";
    $sSql .= "      inner join calendario on ed52_i_codigo = ed57_i_calendario ";
    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where matriculaserie.ed221_c_origem = 'S' AND matricula.ed60_i_codigo = $iCodigo ";
      }

    } elseif ($sDbWhere != '') {
    $sSql2 = " where matriculaserie.ed221_c_origem = 'S' AND $sDbWhere";
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
    function sql_query_matricula_resultado($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

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
      $sSql .= ' from matricula ';
      $sSql .= '   inner join aluno on aluno.ed47_i_codigo = matricula.ed60_i_aluno ';
      $sSql .= '   inner join diario on diario.ed95_i_aluno = aluno.ed47_i_codigo ';
      $sSql .= '   inner join matriculaserie on matriculaserie.ed221_i_matricula = matricula.ed60_i_codigo ';
      $sSql .= '   inner join regencia on regencia.ed59_i_codigo = diario.ed95_i_regencia ';
      $sSql .= '     and regencia.ed59_i_serie = matriculaserie.ed221_i_serie ';
      $sSql .= '   inner join diarioresultado on diarioresultado.ed73_i_diario = diario.ed95_i_codigo ';
      $sSql .= '   inner join procresultado on procresultado.ed43_i_codigo = diarioresultado.ed73_i_procresultado ';
      $sSql .= '   left join diarioresultadorecuperacao on ed116_diarioresultado = diarioresultado.ed73_i_codigo ';
      $sSql .= '   inner join formaavaliacao on formaavaliacao.ed37_i_codigo = procresultado.ed43_i_formaavaliacao ';
      $sSql .= '   inner join resultado on resultado.ed42_i_codigo = procresultado.ed43_i_resultado ';
      $sSql .= '   left join amparo on amparo.ed81_i_diario = diario.ed95_i_codigo ';
      $sSql .= '   left join convencaoamp on convencaoamp.ed250_i_codigo = amparo.ed81_i_convencaoamp ';
      $sSql .= '   left join diariofinal on diariofinal.ed74_i_diario = diario.ed95_i_codigo ';
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
    function sql_query_apagargeral($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

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
      $sSql .= " from matricula " ;
      $sSql .= "      inner join turma on ed57_i_codigo = ed60_i_turma ";
      $sSql .= "      inner join matriculaserie on ed221_i_matricula = ed60_i_codigo ";
      $sSql .= "      inner join serie on ed11_i_codigo = matriculaserie.ed221_i_serie ";
      $sSql .= "      left join matriculamov on ed229_i_matricula = matricula.ed60_i_codigo";
      $sSql2 = '';
      if ($sDbWhere == '') {

        if ($iCodigo != null ){
          $sSql2 .= " where matriculaserie.ed221_c_origem = 'S' AND matricula.ed60_i_codigo = $iCodigo ";
        }

      } elseif ($sDbWhere != '') {
      $sSql2 = " where matriculaserie.ed221_c_origem = 'S' AND $sDbWhere";
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
   function sql_query_alunomatriculado($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

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
    $sSql .= " from matricula " ;
    $sSql .= " inner join turma on ed57_i_codigo = matricula.ed60_i_turma ";
    $sSql .= " inner join matriculaserie on ed221_i_matricula = matricula.ed60_i_codigo ";
    $sSql .= " inner join serie on ed11_i_codigo = matriculaserie.ed221_i_serie ";
    $sSql .= " inner join turno on ed15_i_codigo = turma.ed57_i_turno ";
    $sSql .= " inner join ensino on ed10_i_codigo = serie.ed11_i_ensino ";
    $sSql .= " inner join aluno on ed47_i_codigo = matricula.ed60_i_aluno ";
    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where matriculaserie.ed221_c_origem = 'S' AND matricula.ed60_i_codigo = $iCodigo ";
      }

    } elseif ($sDbWhere != '') {
      $sSql2 = " where matriculaserie.ed221_c_origem = 'S' AND $sDbWhere";
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
  function sql_query_matriculaserie($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

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
    $sSql .= " from matricula " ;
    $sSql .= " inner join matriculaserie on ed221_i_matricula = matricula.ed60_i_codigo ";
    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where matriculaserie.ed221_c_origem = 'S' AND matricula.ed60_i_codigo = $iCodigo ";
      }

    } elseif ($sDbWhere != '') {
    $sSql2 = " where matriculaserie.ed221_c_origem = 'S' AND $sDbWhere";
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
    function sql_query_matriculaanual($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

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
      $sSql .= " from matricula " ;
      $sSql .= "  inner join turma on ed57_i_codigo = ed60_i_turma ";
      $sSql .= "  inner join matriculaserie on ed221_i_matricula = ed60_i_codigo ";
      $sSql .= "  inner join calendario on ed52_i_codigo = ed57_i_calendario ";
      $sSql2 = '';
      if ($sDbWhere == '') {

        if ($iCodigo != null ){
          $sSql2 .= " where matriculaserie.ed221_c_origem = 'S' AND matricula.ed60_i_codigo = $iCodigo ";
        }

      } elseif ($sDbWhere != '') {
      $sSql2 = " where matriculaserie.ed221_c_origem = 'S' AND $sDbWhere";
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
   function sql_query_boletimestat($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

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
    $sSql .= " from matricula " ;
    $sSql .= "      inner join turma on ed57_i_codigo = ed60_i_turma ";
    $sSql .= "      inner join aluno on ed47_i_codigo = ed60_i_aluno ";
    $sSql .= "      inner join matriculaserie on ed221_i_matricula = ed60_i_codigo ";
    $sSql .= "      inner join serie on ed11_i_codigo = ed221_i_serie ";
    $sSql .= "      inner join turno on ed15_i_codigo = ed57_i_turno ";
    $sSql .= "      inner join calendario on ed52_i_codigo = ed57_i_calendario ";
    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where matriculaserie.ed221_c_origem = 'S' AND matricula.ed60_i_codigo = $iCodigo ";
      }

    } elseif ($sDbWhere != '') {
      $sSql2 = " where matriculaserie.ed221_c_origem = 'S' AND $sDbWhere";
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
   function sql_query_expansaomat($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

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
    $sSql .= " from matricula " ;
    $sSql .= "      inner join turma on ed57_i_codigo = ed60_i_turma " ;
    $sSql .= "      inner join aluno on ed47_i_codigo = ed60_i_aluno  " ;
    $sSql .= "      inner join matriculaserie on ed221_i_matricula = ed60_i_codigo " ;
    $sSql .= "      inner join serie on ed11_i_codigo = ed221_i_serie  " ;
    $sSql .= "      inner join calendario on ed52_i_codigo = ed57_i_calendario " ;
    $sSql .= "      inner join ensino on ed10_i_codigo=ed11_i_ensino " ;
    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where matriculaserie.ed221_c_origem = 'S' AND matricula.ed60_i_codigo = $iCodigo ";
      }

    } elseif ($sDbWhere != '') {
      $sSql2 = " where matriculaserie.ed221_c_origem = 'S' AND $sDbWhere";
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
  function sql_query_tipotransf($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

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
    $sSql .= " FROM matricula ";
    $sSql .= "      inner join turma on ed57_i_codigo = ed60_i_turma ";
    $sSql .= "      inner join aluno on ed47_i_codigo = ed60_i_aluno ";
    $sSql .= "      inner join matriculamov on ed229_i_matricula = ed60_i_codigo ";
    $sSql2 = " ";
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " matricula.ed60_i_codigo = $iCodigo ";
      }

    } else if ($sDbWhere != '') {
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
   function sql_query_quadroespecificacao($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

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
    $sSql .= " from matricula " ;
    $sSql .= "      inner join aluno on aluno.ed47_i_codigo = matricula.ed60_i_aluno ";
    $sSql .= "      inner join turma on turma.ed57_i_codigo = matricula.ed60_i_turma ";
    $sSql .= "      inner join calendario on calendario.ed52_i_codigo = turma.ed57_i_calendario ";
    $sSql .= "      inner join matriculaserie on matriculaserie.ed221_i_matricula = matricula.ed60_i_codigo ";
    $sSql .= "      inner join serie on serie.ed11_i_codigo = matriculaserie.ed221_i_serie ";
    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where matriculaserie.ed221_c_origem = 'S' AND matricula.ed60_i_codigo = $iCodigo ";
      }

    } elseif ($sDbWhere != '') {
      $sSql2 = " where matriculaserie.ed221_c_origem = 'S' AND $sDbWhere";
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
   function sql_query_cancelaraval($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

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
    $sSql .= " from matricula " ;
    $sSql .= "      inner join matriculaserie on ed221_i_matricula = ed60_i_codigo" ;
    $sSql .= "      inner join aluno on ed47_i_codigo = ed60_i_aluno" ;
    $sSql .= "      inner join turma on ed57_i_codigo = ed60_i_turma" ;
    $sSql .= "      inner join calendario on ed52_i_codigo = ed57_i_calendario" ;
    $sSql .= "      inner join regencia on ed59_i_turma = ed57_i_codigo" ;
    $sSql .= "      inner join disciplina on ed12_i_codigo = ed59_i_disciplina" ;
    $sSql .= "      inner join caddisciplina on ed232_i_codigo= ed12_i_caddisciplina" ;
    $sSql .= "      inner join diario on ed95_i_regencia = ed59_i_codigo" ;
    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where matriculaserie.ed221_c_origem = 'S' AND matricula.ed60_i_codigo = $iCodigo ";
      }

    } elseif ($sDbWhere != '') {
      $sSql2 = " where matriculaserie.ed221_c_origem = 'S' AND $sDbWhere";
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
  function sql_query_cancelaravalmatricula($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

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
    $sSql .= " from matricula " ;
    $sSql .= "      inner join matriculaserie on ed221_i_matricula = ed60_i_codigo ";
    $sSql .= "      inner join turma on ed57_i_codigo = ed60_i_turma ";
    //$sSql .= "      inner join matriculaserie on ed221_i_matricula = ed60_i_codigo";
    $sSql .= "      inner join calendario on ed52_i_codigo = ed57_i_calendario ";
    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where matriculaserie.ed221_c_origem = 'S' AND matricula.ed60_i_codigo = $iCodigo ";
      }

    } elseif ($sDbWhere != '') {
    $sSql2 = " where matriculaserie.ed221_c_origem = 'S' AND $sDbWhere";
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
    function sql_query_matriculadependencia($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

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

      $sSql .= ' from matricula ' ;
      $sSql .= '      left  join turma               on turma.ed57_i_codigo               = matricula.ed60_i_turma ';
      $sSql .= '      left  join base                on base.ed31_i_codigo                = turma.ed57_i_base ';
      $sSql .= '      left  join turmaserieregimemat on turmaserieregimemat.ed220_i_turma = turma.ed57_i_codigo ';
      $sSql .= '      left  join serieregimemat      on serieregimemat.ed223_i_codigo     = turmaserieregimemat.ed220_i_serieregimemat ';
      $sSql .= '      left  join serie               on serie.ed11_i_codigo               = serieregimemat.ed223_i_serie ';
      $sSql .= '      inner join aluno               on aluno.ed47_i_codigo               = matricula.ed60_i_aluno ';
      $sSql .= '      inner join diario              on diario.ed95_i_aluno               = aluno.ed47_i_codigo ';
      $sSql .= '      left  join diarioavaliacao     on diarioavaliacao.ed72_i_diario     = diario.ed95_i_codigo ';
      $sSql .= '      left  join diarioresultado     on diarioresultado.ed73_i_diario     = diario.ed95_i_codigo ';
      $sSql .= '      left  join diariofinal         on diariofinal.ed74_i_diario         = diario.ed95_i_codigo ';
      $sSql .= '      inner join procresultado       on procresultado.ed43_i_codigo       = diarioresultado.ed73_i_procresultado ';
      $sSql .= '      inner join matriculaserie      on matriculaserie.ed221_i_matricula  = matricula.ed60_i_codigo ';
      $sSql .= '      inner join regencia            on regencia.ed59_i_codigo            = diario.ed95_i_regencia ';
      $sSql .= '                                    and regencia.ed59_i_serie             = matriculaserie.ed221_i_serie ';
      $sSql .= '      inner join formaavaliacao      on formaavaliacao.ed37_i_codigo      = procresultado.ed43_i_formaavaliacao ';
      $sSql .= '      inner join resultado           on resultado.ed42_i_codigo           = procresultado.ed43_i_resultado ';
      $sSql .= '      left join amparo               on amparo.ed81_i_diario              = diario.ed95_i_codigo ';
      $sSql .= '      left join convencaoamp         on convencaoamp.ed250_i_codigo       = amparo.ed81_i_convencaoamp ';
      $sSql2 = '';

      if ($sDbWhere == '') {

        if ($iCodigo != null ){
          $sSql2 .= " where matricula.ed60_i_codigo = $iCodigo ";
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
   function sql_query_diario_classe($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

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
    $sSql .= " from matricula " ;
    $sSql .= "      inner join matriculaserie on ed221_i_matricula = ed60_i_codigo" ;
    $sSql .= "      inner join aluno on ed47_i_codigo = ed60_i_aluno" ;
    $sSql .= "      inner join turma on ed57_i_codigo = ed60_i_turma" ;
    $sSql .= "      inner join calendario on ed52_i_codigo = ed57_i_calendario" ;
    $sSql .= "      inner join regencia on ed59_i_turma = ed57_i_codigo" ;
    $sSql .= "      inner join disciplina on ed12_i_codigo = ed59_i_disciplina" ;
    $sSql .= "      inner join caddisciplina on ed232_i_codigo= ed12_i_caddisciplina" ;
    $sSql .= "      inner join diario on ed95_i_regencia = ed59_i_codigo" ;
    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where matriculaserie.ed221_c_origem = 'S' AND matricula.ed60_i_codigo = $iCodigo ";
      }

    } elseif ($sDbWhere != '') {
      $sSql2 = " where matriculaserie.ed221_c_origem = 'S' AND $sDbWhere";
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
   /**
   * Busca os dados do aluno
   * @param integer $iCodigo
   * @param string $sCampos
   * @param string $sOrdem
   * @param string $sDbWhere
   * @return string
   */
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
    $sSql .= " from matricula " ;
    $sSql .= "      inner join matriculaserie on ed221_i_matricula = ed60_i_codigo";
    $sSql .= "      inner join aluno on ed47_i_codigo = ed60_i_aluno ";

    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where matriculaserie.ed221_c_origem = 'S' AND matricula.ed60_i_codigo = $iCodigo ";
      }

    } elseif ($sDbWhere != '') {
      $sSql2 = " where matriculaserie.ed221_c_origem = 'S' AND $sDbWhere";
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
   /**
   * Busca os dados do aluno
   * @param integer $iCodigo
   * @param string $sCampos
   * @param string $sOrdem
   * @param string $sDbWhere
   * @return string
   */
  function sql_query_aluno_matricula($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

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
    $sSql .= " from matricula " ;
    $sSql .= "      inner join aluno on ed47_i_codigo = ed60_i_aluno ";

    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where  matricula.ed60_i_codigo = $iCodigo ";
      }

    } elseif ($sDbWhere != '') {
      $sSql2 = " where  $sDbWhere";
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
  function sql_query_naturalidade_aluno ( $ed60_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from matricula ";
    $sql .= "      inner join aluno  on  aluno.ed47_i_codigo = matricula.ed60_i_aluno";
    $sql .= "      left join pais   on pais.ed228_i_codigo = aluno.ed47_i_pais";
    $sql .= "      inner join turma  on  turma.ed57_i_codigo = matricula.ed60_i_turma";
    $sql .= "      inner join escola  on  escola.ed18_i_codigo = turma.ed57_i_escola";
    $sql .= "      left join censouf  on  censouf.ed260_i_codigo = escola.ed18_i_censouf";
    $sql .= "      left join censomunic  on  censomunic.ed261_i_codigo = aluno.ed47_i_censomunicnat";
    $sql .= "      inner join turno  on  turno.ed15_i_codigo = turma.ed57_i_turno";
    $sql .= "      inner join sala  on  sala.ed16_i_codigo = turma.ed57_i_sala";
    $sql .= "      inner join calendario  on  calendario.ed52_i_codigo = turma.ed57_i_calendario";
    $sql .= "      inner join base  on  base.ed31_i_codigo = turma.ed57_i_base";
    $sql .= "      inner join cursoedu  on  cursoedu.ed29_i_codigo = base.ed31_i_curso";
    $sql .= "      inner join ensino  on  ensino.ed10_i_codigo = cursoedu.ed29_i_ensino";
    $sql .= "      inner join matriculaserie  on  matriculaserie.ed221_i_matricula = matricula.ed60_i_codigo";
    $sql .= "      inner join serie  on  serie.ed11_i_codigo = matriculaserie.ed221_i_serie";
    $sql .= "      inner join serieregimemat  on  serieregimemat.ed223_i_serie = serie.ed11_i_codigo";
    $sql .= "      inner join turmaserieregimemat  on  turmaserieregimemat.ed220_i_serieregimemat = serieregimemat.ed223_i_codigo";
    $sql .= "                                      and turmaserieregimemat.ed220_i_turma = matricula.ed60_i_turma";
    $sql .= "      inner join procedimento  on  procedimento.ed40_i_codigo = turmaserieregimemat.ed220_i_procedimento";
    $sql .= "      left join turma as turmaant on  turmaant.ed57_i_codigo = matricula.ed60_i_turmaant";
    $sql .= "      left join escola  as escolaant on  escolaant.ed18_i_codigo = turmaant.ed57_i_escola";
    $sql .= "      left join turno  as turnoant on  turnoant.ed15_i_codigo = turmaant.ed57_i_turno";
    $sql .= "      left join sala  as salaant on  salaant.ed16_i_codigo = turmaant.ed57_i_sala";
    $sql .= "      left join calendario  as calendarioant on  calendarioant.ed52_i_codigo = turmaant.ed57_i_calendario";
    $sql .= "      left join base  as baseant on  baseant.ed31_i_codigo = turmaant.ed57_i_base";
    $sql .= "      left join alunoprimat  on  alunoprimat.ed76_i_aluno = aluno.ed47_i_codigo";
    $sql .= "      left join escola  as escolaprimat on  escolaprimat.ed18_i_codigo = alunoprimat.ed76_i_escola";
    $sql .= "      left join escolaproc  on  escolaproc.ed82_i_codigo = alunoprimat.ed76_i_escola";
    $sql2 = "";
    if($dbwhere==""){
      if($ed60_i_codigo!=null ){
        $sql2 .= " where matriculaserie.ed221_c_origem = 'S' AND matricula.ed60_i_codigo = $ed60_i_codigo ";
      }
    }else if($dbwhere != ""){
      $sql2 = " where matriculaserie.ed221_c_origem = 'S' AND $dbwhere";
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
   function sql_query_censo ( $ed60_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
  	$sql .= " from matricula ";
  	$sql .= "      inner join aluno  on  aluno.ed47_i_codigo = matricula.ed60_i_aluno";
  	$sql .= "      left join pais   on pais.ed228_i_codigo = aluno.ed47_i_pais";
  	$sql .= "      inner join turma  on  turma.ed57_i_codigo = matricula.ed60_i_turma";
  	$sql .= "      inner join escola  on  escola.ed18_i_codigo = turma.ed57_i_escola";
  	$sql .= "      left join censouf  on  censouf.ed260_i_codigo = escola.ed18_i_censouf";
  	$sql .= "      left join censomunic  on  censomunic.ed261_i_codigo = escola.ed18_i_censomunic";
  	$sql .= "      inner join turno  on  turno.ed15_i_codigo = turma.ed57_i_turno";
  	$sql .= "      inner join sala  on  sala.ed16_i_codigo = turma.ed57_i_sala";
  	$sql .= "      inner join calendario  on  calendario.ed52_i_codigo = turma.ed57_i_calendario";
  	$sql .= "      inner join base  on  base.ed31_i_codigo = turma.ed57_i_base";
  	$sql .= "      inner join cursoedu  on  cursoedu.ed29_i_codigo = base.ed31_i_curso";
  	$sql .= "      inner join ensino  on  ensino.ed10_i_codigo = cursoedu.ed29_i_ensino";
  	$sql .= "      inner join matriculaserie  on  matriculaserie.ed221_i_matricula = matricula.ed60_i_codigo";
  	$sql .= "      inner join serie  on  serie.ed11_i_codigo = matriculaserie.ed221_i_serie";
  	$sql .= "      inner join serieregimemat  on  serieregimemat.ed223_i_serie = serie.ed11_i_codigo";
  	$sql .= "      inner join turmaserieregimemat  on turmaserieregimemat.ed220_i_serieregimemat = serieregimemat.ed223_i_codigo";
  	$sql .= "                                     and turmaserieregimemat.ed220_i_turma = matricula.ed60_i_turma";
  	$sql .= "      inner join procedimento  on  procedimento.ed40_i_codigo = turmaserieregimemat.ed220_i_procedimento";
  	$sql .= "      left  join turma as turmaant on  turmaant.ed57_i_codigo = matricula.ed60_i_turmaant";
  	$sql .= "      left  join escola  as escolaant on  escolaant.ed18_i_codigo = turmaant.ed57_i_escola";
  	$sql .= "      left  join turno  as turnoant on  turnoant.ed15_i_codigo = turmaant.ed57_i_turno";
  	$sql .= "      left  join sala  as salaant on  salaant.ed16_i_codigo = turmaant.ed57_i_sala";
  	$sql .= "      left  join calendario  as calendarioant on  calendarioant.ed52_i_codigo = turmaant.ed57_i_calendario";
  	$sql .= "      left  join base  as baseant on  baseant.ed31_i_codigo = turmaant.ed57_i_base";
  	$sql .= "      left  join alunoprimat  on  alunoprimat.ed76_i_aluno = aluno.ed47_i_codigo";
  	$sql .= "      left  join escola  as escolaprimat on  escolaprimat.ed18_i_codigo = alunoprimat.ed76_i_escola";
  	$sql .= "      left  join escolaproc  on  escolaproc.ed82_i_codigo = alunoprimat.ed76_i_escola";
  	$sql .= "      left  join censocartorio on censocartorio.ed291_i_codigo = aluno.ed47_i_censocartorio";
  	$sql .= "      left  join turnoreferente  on turnoreferente.ed231_i_turno = turno.ed15_i_codigo";
    $sql .= "      left  join turmacensoturma on turmacensoturma.ed343_turma = turma.ed57_i_codigo";
    $sql .= "      left  join turmacenso      on turmacenso.ed342_sequencial = turmacensoturma.ed343_turmacenso";
    $sql .= "      inner join turmacensoetapa on turmacensoetapa.ed132_turma = turma.ed57_i_codigo";

  	$sql2 = "";
  	if($dbwhere==""){
  		if($ed60_i_codigo!=null ){
  			$sql2 .= " where matriculaserie.ed221_c_origem = 'S' AND matricula.ed60_i_codigo = $ed60_i_codigo ";
  		}
  	}else if($dbwhere != ""){
  		$sql2 = " where matriculaserie.ed221_c_origem = 'S' AND $dbwhere";
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

  function sql_query_aluno_transferido ( $ed60_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from matricula ";
     $sql .= "      inner join aluno  on  aluno.ed47_i_codigo = matricula.ed60_i_aluno";
     $sql .= "      inner join turma  on  turma.ed57_i_codigo = matricula.ed60_i_turma";
     $sql .= "      inner join matriculaserie  on  matriculaserie.ed221_i_matricula = matricula.ed60_i_codigo";
     $sql .= "      inner join serie  on  serie.ed11_i_codigo = matriculaserie.ed221_i_serie";
     $sql .= "      inner join serieregimemat  on  serieregimemat.ed223_i_serie = serie.ed11_i_codigo";
     $sql .= "      inner join turmaserieregimemat  on  turmaserieregimemat.ed220_i_serieregimemat = serieregimemat.ed223_i_codigo";
     $sql .= "                                      and turmaserieregimemat.ed220_i_turma = matricula.ed60_i_turma";
     $sql .= "      left join turma as turmaant on  turmaant.ed57_i_codigo = matricula.ed60_i_turmaant";
     $sql .= "      left join alunoprimat  on  alunoprimat.ed76_i_aluno = aluno.ed47_i_codigo";
     $sql .= "      left join alunotransfturma on alunotransfturma.ed69_i_matricula = matricula.ed60_i_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($ed60_i_codigo!=null ){
         $sql2 .= " where matriculaserie.ed221_c_origem = 'S' AND matricula.ed60_i_codigo = $ed60_i_codigo ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where matriculaserie.ed221_c_origem = 'S' AND $dbwhere";
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

  /**
   * Busca os dados do aluno para a exportação do CENSO da Situação do Aluno
   * @param integer $iCodigo
   * @param string $sCampos
   * @param integer $sOrdem
   * @param string $sDbWhere
   * @return string
   */
  function sql_query_censo_situacao_aluno( $iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '' ) {

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


    $sSql  .= " FROM matricula ";
    $sSql  .= "   INNER JOIN aluno           ON aluno.ed47_i_codigo              = matricula.ed60_i_aluno ";
    $sSql  .= "   INNER JOIN turma           ON turma.ed57_i_codigo              = matricula.ed60_i_turma ";
    $sSql  .= "   INNER JOIN escola          ON escola.ed18_i_codigo             = turma.ed57_i_escola ";
    $sSql  .= "   INNER JOIN calendario      ON calendario.ed52_i_codigo         = turma.ed57_i_calendario ";
    $sSql  .= "   INNER JOIN base            ON base.ed31_i_codigo               = turma.ed57_i_base ";
    $sSql  .= "   INNER JOIN cursoedu        ON cursoedu.ed29_i_codigo           = base.ed31_i_curso ";
    $sSql  .= "   INNER JOIN ensino          ON ensino.ed10_i_codigo             = cursoedu.ed29_i_ensino ";
    $sSql  .= "   INNER JOIN matriculaserie  ON matriculaserie.ed221_i_matricula = matricula.ed60_i_codigo ";
    $sSql  .= "   INNER JOIN serie           ON serie.ed11_i_codigo              = matriculaserie.ed221_i_serie ";
    $sSql  .= "   INNER JOIN turmacensoetapa ON turmacensoetapa.ed132_turma      = turma.ed57_i_codigo ";
    $sSql  .= "   LEFT  JOIN alunomatcenso   ON alunomatcenso.ed280_i_aluno      = aluno.ed47_i_codigo ";
    $sSql  .= "                             AND alunomatcenso.ed280_i_ano        = calendario.ed52_i_ano ";
    $sSql  .= "                             AND alunomatcenso.ed280_i_turmacenso = turma.ed57_i_codigoinep ";
    $sSql  .= "   INNER JOIN seriecensoetapa ON seriecensoetapa.ed133_serie      = serie.ed11_i_codigo ";
    $sSql  .= "                             AND seriecensoetapa.ed133_ano        = calendario.ed52_i_ano ";

    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where aluno.ed47_i_codigo = $iCodigo ";
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