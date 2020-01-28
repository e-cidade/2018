<?php
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
//CLASSE DA ENTIDADE regencia
class cl_regencia {
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
   var $ed59_i_codigo = 0;
   var $ed59_i_turma = 0;
   var $ed59_i_disciplina = 0;
   var $ed59_i_qtdperiodo = 0;
   var $ed59_c_condicao = null;
   var $ed59_c_freqglob = null;
   var $ed59_c_ultatualiz = null;
   var $ed59_d_dataatualiz_dia = null;
   var $ed59_d_dataatualiz_mes = null;
   var $ed59_d_dataatualiz_ano = null;
   var $ed59_d_dataatualiz = null;
   var $ed59_c_encerrada = null;
   var $ed59_i_ordenacao = 0;
   var $ed59_i_serie = 0;
   var $ed59_lancarhistorico = 't';
   var $ed59_caracterreprobatorio = 'f'; 
   var $ed59_basecomum = 'f'; 
   var $ed59_procedimento = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 ed59_i_codigo = int8 = Código
                 ed59_i_turma = int8 = Turma
                 ed59_i_disciplina = int8 = Disciplina
                 ed59_i_qtdperiodo = int4 = Quantidade Horas - Aula 
                 ed59_c_condicao = char(2) = Matrícula
                 ed59_c_freqglob = char(2) = Frequência
                 ed59_c_ultatualiz = char(10) = Última Atualização
                 ed59_d_dataatualiz = date = Data Atualização
                 ed59_c_encerrada = char(1) = Encerrada
                 ed59_i_ordenacao = int4 = Ordenar Disciplinas
                 ed59_i_serie = int8 = Etapa
                 ed59_lancarhistorico = bool = Lançar no Histórico
                 ed59_caracterreprobatorio = bool = Carácter Reprobatório 
                 ed59_basecomum = bool = Base Comum 
                 ed59_procedimento = int8 = Código do Procedimento 
                 ";
   //funcao construtor da classe
   function cl_regencia() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("regencia");
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]."?ed59_i_turma=".@$GLOBALS["HTTP_POST_VARS"]["ed59_i_turma"]."&ed57_c_descr=".@$GLOBALS["HTTP_POST_VARS"]["ed57_c_descr"]."&ed59_i_serie=".@$GLOBALS["HTTP_POST_VARS"]["ed59_i_serie"]."&ed11_c_descr=".@$GLOBALS["HTTP_POST_VARS"]["ed11_c_descr"]."&frequencia=".@$GLOBALS["HTTP_POST_VARS"]["frequencia"]);
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
       $this->ed59_i_codigo = ($this->ed59_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed59_i_codigo"]:$this->ed59_i_codigo);
       $this->ed59_i_turma = ($this->ed59_i_turma == ""?@$GLOBALS["HTTP_POST_VARS"]["ed59_i_turma"]:$this->ed59_i_turma);
       $this->ed59_i_disciplina = ($this->ed59_i_disciplina == ""?@$GLOBALS["HTTP_POST_VARS"]["ed59_i_disciplina"]:$this->ed59_i_disciplina);
       $this->ed59_i_qtdperiodo = ($this->ed59_i_qtdperiodo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed59_i_qtdperiodo"]:$this->ed59_i_qtdperiodo);
       $this->ed59_c_condicao = ($this->ed59_c_condicao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed59_c_condicao"]:$this->ed59_c_condicao);
       $this->ed59_c_freqglob = ($this->ed59_c_freqglob == ""?@$GLOBALS["HTTP_POST_VARS"]["ed59_c_freqglob"]:$this->ed59_c_freqglob);
       $this->ed59_c_ultatualiz = ($this->ed59_c_ultatualiz == ""?@$GLOBALS["HTTP_POST_VARS"]["ed59_c_ultatualiz"]:$this->ed59_c_ultatualiz);
       if($this->ed59_d_dataatualiz == ""){
         $this->ed59_d_dataatualiz_dia = ($this->ed59_d_dataatualiz_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed59_d_dataatualiz_dia"]:$this->ed59_d_dataatualiz_dia);
         $this->ed59_d_dataatualiz_mes = ($this->ed59_d_dataatualiz_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed59_d_dataatualiz_mes"]:$this->ed59_d_dataatualiz_mes);
         $this->ed59_d_dataatualiz_ano = ($this->ed59_d_dataatualiz_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed59_d_dataatualiz_ano"]:$this->ed59_d_dataatualiz_ano);
         if($this->ed59_d_dataatualiz_dia != ""){
            $this->ed59_d_dataatualiz = $this->ed59_d_dataatualiz_ano."-".$this->ed59_d_dataatualiz_mes."-".$this->ed59_d_dataatualiz_dia;
         }
       }
       $this->ed59_c_encerrada = ($this->ed59_c_encerrada == ""?@$GLOBALS["HTTP_POST_VARS"]["ed59_c_encerrada"]:$this->ed59_c_encerrada);
       $this->ed59_i_ordenacao = ($this->ed59_i_ordenacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed59_i_ordenacao"]:$this->ed59_i_ordenacao);
       $this->ed59_i_serie = ($this->ed59_i_serie == ""?@$GLOBALS["HTTP_POST_VARS"]["ed59_i_serie"]:$this->ed59_i_serie);
       $this->ed59_lancarhistorico = ($this->ed59_lancarhistorico == "f"?@$GLOBALS["HTTP_POST_VARS"]["ed59_lancarhistorico"]:$this->ed59_lancarhistorico);
       $this->ed59_caracterreprobatorio = ($this->ed59_caracterreprobatorio == "f"?@$GLOBALS["HTTP_POST_VARS"]["ed59_caracterreprobatorio"]:$this->ed59_caracterreprobatorio);
       $this->ed59_basecomum = ($this->ed59_basecomum == "f"?@$GLOBALS["HTTP_POST_VARS"]["ed59_basecomum"]:$this->ed59_basecomum);
       $this->ed59_procedimento = ($this->ed59_procedimento == ""?@$GLOBALS["HTTP_POST_VARS"]["ed59_procedimento"]:$this->ed59_procedimento);
     }else{
       $this->ed59_i_codigo = ($this->ed59_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed59_i_codigo"]:$this->ed59_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed59_i_codigo){
      $this->atualizacampos();
     if($this->ed59_i_turma == null ){
       $this->erro_sql = " Campo Turma não informado.";
       $this->erro_campo = "ed59_i_turma";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed59_i_disciplina == null ){
       $this->erro_sql = " Campo Disciplina não informado.";
       $this->erro_campo = "ed59_i_disciplina";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed59_i_qtdperiodo == null ){
       $this->erro_sql = " Campo Quantidade Horas - Aula não informado.";
       $this->erro_campo = "ed59_i_qtdperiodo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed59_c_condicao == null ){
       $this->erro_sql = " Campo Condição não informado.";
       $this->erro_campo = "ed59_c_condicao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed59_d_dataatualiz == null || $this->ed59_d_dataatualiz == "null") {
       $this->ed59_d_dataatualiz = "null";
     }
     if($this->ed59_c_encerrada == null ){
       $this->erro_sql = " Campo Encerrada não informado.";
       $this->erro_campo = "ed59_c_encerrada";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed59_i_ordenacao == null ){
       $this->ed59_i_ordenacao = "0";
     }
     if($this->ed59_i_serie == null ){
       $this->erro_sql = " Campo Etapa não informado.";
       $this->erro_campo = "ed59_i_serie";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed59_lancarhistorico == null ){
       $this->erro_sql = " Campo Lançar no Histórico não informado.";
       $this->erro_campo = "ed59_lancarhistorico";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed59_caracterreprobatorio == null ){ 
       $this->erro_sql = " Campo Carácter Reprobatório não informado.";
       $this->erro_campo = "ed59_caracterreprobatorio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed59_basecomum == null ){ 
       $this->erro_sql = " Campo Base Comum não informado.";
       $this->erro_campo = "ed59_basecomum";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed59_procedimento == null ){ 
       $this->erro_sql = " Campo Código do Procedimento não informado.";
       $this->erro_campo = "ed59_procedimento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed59_i_codigo == "" || $ed59_i_codigo == null ){
       $result = db_query("select nextval('regencia_ed59_i_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: regencia_ed59_i_codigo_seq do campo: ed59_i_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->ed59_i_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from regencia_ed59_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed59_i_codigo)){
         $this->erro_sql = " Campo ed59_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed59_i_codigo = $ed59_i_codigo;
       }
     }
     if(($this->ed59_i_codigo == null) || ($this->ed59_i_codigo == "") ){
       $this->erro_sql = " Campo ed59_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into regencia(
                                       ed59_i_codigo
                                      ,ed59_i_turma
                                      ,ed59_i_disciplina
                                      ,ed59_i_qtdperiodo
                                      ,ed59_c_condicao
                                      ,ed59_c_freqglob
                                      ,ed59_c_ultatualiz
                                      ,ed59_d_dataatualiz
                                      ,ed59_c_encerrada
                                      ,ed59_i_ordenacao
                                      ,ed59_i_serie
                                      ,ed59_lancarhistorico
                                      ,ed59_caracterreprobatorio 
                                      ,ed59_basecomum 
                                      ,ed59_procedimento 
                       )
                values (
                                $this->ed59_i_codigo
                               ,$this->ed59_i_turma
                               ,$this->ed59_i_disciplina
                               ,$this->ed59_i_qtdperiodo
                               ,'$this->ed59_c_condicao'
                               ,'$this->ed59_c_freqglob'
                               ,'$this->ed59_c_ultatualiz'
                               ,".($this->ed59_d_dataatualiz == "null" || $this->ed59_d_dataatualiz == ""?"null":"'".$this->ed59_d_dataatualiz."'")."
                               ,'$this->ed59_c_encerrada'
                               ,$this->ed59_i_ordenacao
                               ,$this->ed59_i_serie
                               ,'$this->ed59_lancarhistorico'
                               ,'$this->ed59_caracterreprobatorio' 
                               ,'$this->ed59_basecomum' 
                               ,$this->ed59_procedimento 
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Regência da Turma ($this->ed59_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Regência da Turma já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Regência da Turma ($this->ed59_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed59_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed59_i_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008498,'$this->ed59_i_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,1010084,1008498,'','".AddSlashes(pg_result($resaco,0,'ed59_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010084,1008499,'','".AddSlashes(pg_result($resaco,0,'ed59_i_turma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010084,1008500,'','".AddSlashes(pg_result($resaco,0,'ed59_i_disciplina'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010084,1008501,'','".AddSlashes(pg_result($resaco,0,'ed59_i_qtdperiodo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010084,1008502,'','".AddSlashes(pg_result($resaco,0,'ed59_c_condicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010084,1008504,'','".AddSlashes(pg_result($resaco,0,'ed59_c_freqglob'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010084,1008505,'','".AddSlashes(pg_result($resaco,0,'ed59_c_ultatualiz'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010084,1008506,'','".AddSlashes(pg_result($resaco,0,'ed59_d_dataatualiz'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010084,1008503,'','".AddSlashes(pg_result($resaco,0,'ed59_c_encerrada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010084,14692,'','".AddSlashes(pg_result($resaco,0,'ed59_i_ordenacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010084,15222,'','".AddSlashes(pg_result($resaco,0,'ed59_i_serie'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010084,20321,'','".AddSlashes(pg_result($resaco,0,'ed59_lancarhistorico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010084,20661,'','".AddSlashes(pg_result($resaco,0,'ed59_caracterreprobatorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010084,20662,'','".AddSlashes(pg_result($resaco,0,'ed59_basecomum'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010084,20826,'','".AddSlashes(pg_result($resaco,0,'ed59_procedimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($ed59_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update regencia set ";
     $virgula = "";
     if(trim($this->ed59_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed59_i_codigo"])){
       $sql  .= $virgula." ed59_i_codigo = $this->ed59_i_codigo ";
       $virgula = ",";
       if(trim($this->ed59_i_codigo) == null ){
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "ed59_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed59_i_turma)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed59_i_turma"])){
       $sql  .= $virgula." ed59_i_turma = $this->ed59_i_turma ";
       $virgula = ",";
       if(trim($this->ed59_i_turma) == null ){
         $this->erro_sql = " Campo Turma não informado.";
         $this->erro_campo = "ed59_i_turma";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed59_i_disciplina)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed59_i_disciplina"])){
       $sql  .= $virgula." ed59_i_disciplina = $this->ed59_i_disciplina ";
       $virgula = ",";
       if(trim($this->ed59_i_disciplina) == null ){
         $this->erro_sql = " Campo Disciplina não informado.";
         $this->erro_campo = "ed59_i_disciplina";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed59_i_qtdperiodo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed59_i_qtdperiodo"])){
       $sql  .= $virgula." ed59_i_qtdperiodo = $this->ed59_i_qtdperiodo ";
       $virgula = ",";
       if(trim($this->ed59_i_qtdperiodo) == null ){
         $this->erro_sql = " Campo Quantidade Horas - Aula não informado.";
         $this->erro_campo = "ed59_i_qtdperiodo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed59_c_condicao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed59_c_condicao"])){
       $sql  .= $virgula." ed59_c_condicao = '$this->ed59_c_condicao' ";
       $virgula = ",";
       if(trim($this->ed59_c_condicao) == null ){
         $this->erro_sql = " Campo Matrícula não informado.";
         $this->erro_campo = "ed59_c_condicao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed59_c_freqglob)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed59_c_freqglob"])){
       $sql  .= $virgula." ed59_c_freqglob = '$this->ed59_c_freqglob' ";
       $virgula = ",";
     }
     if(trim($this->ed59_c_ultatualiz)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed59_c_ultatualiz"])){
       $sql  .= $virgula." ed59_c_ultatualiz = '$this->ed59_c_ultatualiz' ";
       $virgula = ",";
     }
   if(trim($this->ed59_d_dataatualiz)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed59_d_dataatualiz_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed59_d_dataatualiz_dia"] !="") ){

     	 if ($this->ed59_d_dataatualiz == "null") {
     	 	 $sql  .= $virgula." ed59_d_dataatualiz = null ";
     	 } else {
     	   $sql  .= $virgula." ed59_d_dataatualiz = '$this->ed59_d_dataatualiz' ";
     	 }

     	 $virgula = ",";

     } else {

       if(isset($GLOBALS["HTTP_POST_VARS"]["ed59_d_dataatualiz_dia"])){
         $sql  .= $virgula." ed59_d_dataatualiz = null ";
         $virgula = ",";
       }

     }
     if(trim($this->ed59_c_encerrada)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed59_c_encerrada"])){
       $sql  .= $virgula." ed59_c_encerrada = '$this->ed59_c_encerrada' ";
       $virgula = ",";
       if(trim($this->ed59_c_encerrada) == null ){
         $this->erro_sql = " Campo Encerrada não informado.";
         $this->erro_campo = "ed59_c_encerrada";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed59_i_ordenacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed59_i_ordenacao"])){
        if(trim($this->ed59_i_ordenacao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed59_i_ordenacao"])){
           $this->ed59_i_ordenacao = "0" ;
        }
       $sql  .= $virgula." ed59_i_ordenacao = $this->ed59_i_ordenacao ";
       $virgula = ",";
     }
     if(trim($this->ed59_i_serie)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed59_i_serie"])){
       $sql  .= $virgula." ed59_i_serie = $this->ed59_i_serie ";
       $virgula = ",";
       if(trim($this->ed59_i_serie) == null ){
         $this->erro_sql = " Campo Etapa não informado.";
         $this->erro_campo = "ed59_i_serie";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed59_lancarhistorico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed59_lancarhistorico"])){
       $sql  .= $virgula." ed59_lancarhistorico = '$this->ed59_lancarhistorico' ";
       $virgula = ",";
       if(trim($this->ed59_lancarhistorico) == null ){
         $this->erro_sql = " Campo Lançar no Histórico não informado.";
         $this->erro_campo = "ed59_lancarhistorico";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed59_caracterreprobatorio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed59_caracterreprobatorio"])){ 
       $sql  .= $virgula." ed59_caracterreprobatorio = '$this->ed59_caracterreprobatorio' ";
       $virgula = ",";
       if(trim($this->ed59_caracterreprobatorio) == null ){ 
         $this->erro_sql = " Campo Carácter Reprobatório não informado.";
         $this->erro_campo = "ed59_caracterreprobatorio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed59_basecomum)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed59_basecomum"])){ 
       $sql  .= $virgula." ed59_basecomum = '$this->ed59_basecomum' ";
       $virgula = ",";
       if(trim($this->ed59_basecomum) == null ){ 
         $this->erro_sql = " Campo Base Comum não informado.";
         $this->erro_campo = "ed59_basecomum";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed59_procedimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed59_procedimento"])){ 
       $sql  .= $virgula." ed59_procedimento = $this->ed59_procedimento ";
       $virgula = ",";
       if(trim($this->ed59_procedimento) == null ){ 
         $this->erro_sql = " Campo Código do Procedimento não informado.";
         $this->erro_campo = "ed59_procedimento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed59_i_codigo!=null){
       $sql .= " ed59_i_codigo = $this->ed59_i_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed59_i_codigo));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1008498,'$this->ed59_i_codigo','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed59_i_codigo"]) || $this->ed59_i_codigo != "")
             $resac = db_query("insert into db_acount values($acount,1010084,1008498,'".AddSlashes(pg_result($resaco,$conresaco,'ed59_i_codigo'))."','$this->ed59_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed59_i_turma"]) || $this->ed59_i_turma != "")
             $resac = db_query("insert into db_acount values($acount,1010084,1008499,'".AddSlashes(pg_result($resaco,$conresaco,'ed59_i_turma'))."','$this->ed59_i_turma',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed59_i_disciplina"]) || $this->ed59_i_disciplina != "")
             $resac = db_query("insert into db_acount values($acount,1010084,1008500,'".AddSlashes(pg_result($resaco,$conresaco,'ed59_i_disciplina'))."','$this->ed59_i_disciplina',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed59_i_qtdperiodo"]) || $this->ed59_i_qtdperiodo != "")
             $resac = db_query("insert into db_acount values($acount,1010084,1008501,'".AddSlashes(pg_result($resaco,$conresaco,'ed59_i_qtdperiodo'))."','$this->ed59_i_qtdperiodo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed59_c_condicao"]) || $this->ed59_c_condicao != "")
             $resac = db_query("insert into db_acount values($acount,1010084,1008502,'".AddSlashes(pg_result($resaco,$conresaco,'ed59_c_condicao'))."','$this->ed59_c_condicao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed59_c_freqglob"]) || $this->ed59_c_freqglob != "")
             $resac = db_query("insert into db_acount values($acount,1010084,1008504,'".AddSlashes(pg_result($resaco,$conresaco,'ed59_c_freqglob'))."','$this->ed59_c_freqglob',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed59_c_ultatualiz"]) || $this->ed59_c_ultatualiz != "")
             $resac = db_query("insert into db_acount values($acount,1010084,1008505,'".AddSlashes(pg_result($resaco,$conresaco,'ed59_c_ultatualiz'))."','$this->ed59_c_ultatualiz',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed59_d_dataatualiz"]) || $this->ed59_d_dataatualiz != "")
             $resac = db_query("insert into db_acount values($acount,1010084,1008506,'".AddSlashes(pg_result($resaco,$conresaco,'ed59_d_dataatualiz'))."','$this->ed59_d_dataatualiz',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed59_c_encerrada"]) || $this->ed59_c_encerrada != "")
             $resac = db_query("insert into db_acount values($acount,1010084,1008503,'".AddSlashes(pg_result($resaco,$conresaco,'ed59_c_encerrada'))."','$this->ed59_c_encerrada',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed59_i_ordenacao"]) || $this->ed59_i_ordenacao != "")
             $resac = db_query("insert into db_acount values($acount,1010084,14692,'".AddSlashes(pg_result($resaco,$conresaco,'ed59_i_ordenacao'))."','$this->ed59_i_ordenacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed59_i_serie"]) || $this->ed59_i_serie != "")
             $resac = db_query("insert into db_acount values($acount,1010084,15222,'".AddSlashes(pg_result($resaco,$conresaco,'ed59_i_serie'))."','$this->ed59_i_serie',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed59_lancarhistorico"]) || $this->ed59_lancarhistorico != "")
             $resac = db_query("insert into db_acount values($acount,1010084,20321,'".AddSlashes(pg_result($resaco,$conresaco,'ed59_lancarhistorico'))."','$this->ed59_lancarhistorico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed59_caracterreprobatorio"]) || $this->ed59_caracterreprobatorio != "")
             $resac = db_query("insert into db_acount values($acount,1010084,20661,'".AddSlashes(pg_result($resaco,$conresaco,'ed59_caracterreprobatorio'))."','$this->ed59_caracterreprobatorio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed59_basecomum"]) || $this->ed59_basecomum != "")
             $resac = db_query("insert into db_acount values($acount,1010084,20662,'".AddSlashes(pg_result($resaco,$conresaco,'ed59_basecomum'))."','$this->ed59_basecomum',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed59_procedimento"]) || $this->ed59_procedimento != "")
             $resac = db_query("insert into db_acount values($acount,1010084,20826,'".AddSlashes(pg_result($resaco,$conresaco,'ed59_procedimento'))."','$this->ed59_procedimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Regência da Turma nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed59_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Regência da Turma nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed59_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed59_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($ed59_i_codigo=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($ed59_i_codigo));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1008498,'$ed59_i_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,1010084,1008498,'','".AddSlashes(pg_result($resaco,$iresaco,'ed59_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010084,1008499,'','".AddSlashes(pg_result($resaco,$iresaco,'ed59_i_turma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010084,1008500,'','".AddSlashes(pg_result($resaco,$iresaco,'ed59_i_disciplina'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010084,1008501,'','".AddSlashes(pg_result($resaco,$iresaco,'ed59_i_qtdperiodo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010084,1008502,'','".AddSlashes(pg_result($resaco,$iresaco,'ed59_c_condicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010084,1008504,'','".AddSlashes(pg_result($resaco,$iresaco,'ed59_c_freqglob'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010084,1008505,'','".AddSlashes(pg_result($resaco,$iresaco,'ed59_c_ultatualiz'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010084,1008506,'','".AddSlashes(pg_result($resaco,$iresaco,'ed59_d_dataatualiz'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010084,1008503,'','".AddSlashes(pg_result($resaco,$iresaco,'ed59_c_encerrada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010084,14692,'','".AddSlashes(pg_result($resaco,$iresaco,'ed59_i_ordenacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010084,15222,'','".AddSlashes(pg_result($resaco,$iresaco,'ed59_i_serie'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010084,20321,'','".AddSlashes(pg_result($resaco,$iresaco,'ed59_lancarhistorico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010084,20661,'','".AddSlashes(pg_result($resaco,$iresaco,'ed59_caracterreprobatorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010084,20662,'','".AddSlashes(pg_result($resaco,$iresaco,'ed59_basecomum'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010084,20826,'','".AddSlashes(pg_result($resaco,$iresaco,'ed59_procedimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from regencia
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed59_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed59_i_codigo = $ed59_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Regência da Turma nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed59_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Regência da Turma nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed59_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed59_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:regencia";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $ed59_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from regencia ";
     $sql .= "      inner join disciplina  on  disciplina.ed12_i_codigo = regencia.ed59_i_disciplina";
     $sql .= "      inner join caddisciplina on ed232_i_codigo= ed12_i_caddisciplina";
   //  $sql .= "      left  join areaconhecimento on areaconhecimento.ed293_sequencial = caddisciplina.ed232_areaconhecimento ";
     $sql .= "      inner join turma  on  turma.ed57_i_codigo = regencia.ed59_i_turma";
     $sql .= "      inner join ensino  on  ensino.ed10_i_codigo = disciplina.ed12_i_ensino";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = turma.ed57_i_escola";
     $sql .= "      inner join turno  on  turno.ed15_i_codigo = turma.ed57_i_turno";
     $sql .= "      inner join sala  on  sala.ed16_i_codigo = turma.ed57_i_sala";
     $sql .= "      inner join calendario  on  calendario.ed52_i_codigo = turma.ed57_i_calendario";
     $sql .= "      inner join base  on  base.ed31_i_codigo = turma.ed57_i_base";
     $sql .= "      left join basediscglob  on  basediscglob.ed89_i_codigo = base.ed31_i_codigo";
     $sql .= "      inner join cursoedu  on  cursoedu.ed29_i_codigo = base.ed31_i_curso";
     $sql .= "      inner join serie  on  serie.ed11_i_codigo = regencia.ed59_i_serie";
     $sql .= "      inner join serieregimemat  on  serieregimemat.ed223_i_serie = serie.ed11_i_codigo";
     $sql .= "      inner join turmaserieregimemat  on  turmaserieregimemat.ed220_i_serieregimemat = serieregimemat.ed223_i_codigo";
     $sql .= "                                      and turmaserieregimemat.ed220_i_turma = regencia.ed59_i_turma";
     $sql .= "      inner join procedimento  on  procedimento.ed40_i_codigo = turmaserieregimemat.ed220_i_procedimento";
     $sql2 = "";
     if($dbwhere==""){
       if($ed59_i_codigo!=null ){
         $sql2 .= " where regencia.ed59_i_codigo = $ed59_i_codigo ";
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
   function sql_query_file ( $ed59_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from regencia ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed59_i_codigo!=null ){
         $sql2 .= " where regencia.ed59_i_codigo = $ed59_i_codigo ";
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
   function sql_query_avaliacao ( $ed59_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from regencia ";
    $sql .= "      inner join disciplina           on  disciplina.ed12_i_codigo = regencia.ed59_i_disciplina";
    $sql .= "      inner join caddisciplina        on  ed232_i_codigo= ed12_i_caddisciplina";
    $sql .= "      inner join turma                on  turma.ed57_i_codigo = regencia.ed59_i_turma";
    $sql .= "      inner join ensino               on  ensino.ed10_i_codigo = disciplina.ed12_i_ensino";
    $sql .= "      inner join escola               on  escola.ed18_i_codigo = turma.ed57_i_escola";
    $sql .= "      inner join turno                on  turno.ed15_i_codigo = turma.ed57_i_turno";
    $sql .= "      inner join calendario           on  calendario.ed52_i_codigo = turma.ed57_i_calendario";
    $sql .= "      inner join serie                on  serie.ed11_i_codigo = regencia.ed59_i_serie";
    $sql .= "      inner join serieregimemat       on  serieregimemat.ed223_i_serie = serie.ed11_i_codigo";
    $sql .= "      inner join turmaserieregimemat  on  turmaserieregimemat.ed220_i_serieregimemat = serieregimemat.ed223_i_codigo";
    $sql .= "                                      and turmaserieregimemat.ed220_i_turma = regencia.ed59_i_turma";
    $sql .= "      inner join procedimento         on  procedimento.ed40_i_codigo = turmaserieregimemat.ed220_i_procedimento";
    $sql .= '      inner join procavaliacao        on  ed41_i_procedimento = ed40_i_codigo ';
    $sql .= '      inner join periodoavaliacao     on  ed09_i_codigo = ed41_i_periodoavaliacao ';
    $sql2 = "";
    if($dbwhere==""){
      if($ed59_i_codigo!=null ){
        $sql2 .= " where regencia.ed59_i_codigo = $ed59_i_codigo ";
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
   function sql_query_censo ( $ed59_i_codigo=null,$campos="*",$ordem=null,$dbwhere="") {

    $sql = "select ";
    if ($campos != "*" ) {

      $campos_sql = split("#",$campos);
      $virgula    = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {

        $sql     .= $virgula.$campos_sql[$i];
        $virgula  = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from regencia ";
    $sql .= "      inner join disciplina  on  disciplina.ed12_i_codigo = regencia.ed59_i_disciplina";
    $sql .= "      inner join caddisciplina on ed232_i_codigo= ed12_i_caddisciplina";
    $sql .= "      left  join areaconhecimento on areaconhecimento.ed293_sequencial = caddisciplina.ed232_areaconhecimento ";
    $sql .= "      inner join censocaddisciplina on censocaddisciplina.ed294_caddisciplina = caddisciplina.ed232_i_codigo";
    $sql .= "      inner join censodisciplina    on censodisciplina.ed265_i_codigo         = censocaddisciplina.ed294_censodisciplina ";
    $sql .= "      inner join turma  on  turma.ed57_i_codigo = regencia.ed59_i_turma";
    $sql .= "      inner join ensino  on  ensino.ed10_i_codigo = disciplina.ed12_i_ensino";
    $sql .= "      inner join escola  on  escola.ed18_i_codigo = turma.ed57_i_escola";
    $sql .= "      inner join turno  on  turno.ed15_i_codigo = turma.ed57_i_turno";
    $sql .= "      inner join sala  on  sala.ed16_i_codigo = turma.ed57_i_sala";
    $sql .= "      inner join calendario  on  calendario.ed52_i_codigo = turma.ed57_i_calendario";
    $sql .= "      inner join base  on  base.ed31_i_codigo = turma.ed57_i_base";
    $sql .= "      left join basediscglob  on  basediscglob.ed89_i_codigo = base.ed31_i_codigo";
    $sql .= "      inner join cursoedu  on  cursoedu.ed29_i_codigo = base.ed31_i_curso";
    $sql .= "      inner join serie  on  serie.ed11_i_codigo = regencia.ed59_i_serie";
    $sql .= "      inner join serieregimemat  on  serieregimemat.ed223_i_serie = serie.ed11_i_codigo";
    $sql .= "      inner join turmaserieregimemat  on  turmaserieregimemat.ed220_i_serieregimemat = serieregimemat.ed223_i_codigo";
    $sql .= "                                      and turmaserieregimemat.ed220_i_turma = regencia.ed59_i_turma";
    $sql .= "      inner join procedimento  on  procedimento.ed40_i_codigo = turmaserieregimemat.ed220_i_procedimento";
    $sql2 = "";
    if ($dbwhere == "") {

      if ($ed59_i_codigo != null) {
        $sql2 .= " where regencia.ed59_i_codigo = $ed59_i_codigo ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {

      $sql        .= " order by ";
      $campos_sql  = split("#",$ordem);
      $virgula     = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {

      $sql     .= $virgula.$campos_sql[$i];
      $virgula  = ",";
    }
  }
  return $sql;
  }
   function sql_query_disciplina_censo ( $ed59_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){

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
     $sql .= " from regencia ";
     $sql .= "      inner join disciplina      on disciplina.ed12_i_codigo = regencia.ed59_i_disciplina";
     $sql .= "      inner join caddisciplina   on ed232_i_codigo = ed12_i_caddisciplina";
     $sql2 = "";
     if($dbwhere==""){
       if($ed59_i_codigo!=null ){
         $sql2 .= " where regencia.ed59_i_codigo = $ed59_i_codigo ";
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

  function sql_query_regenciahorario( $ed59_i_codigo = null, $campos = "*", $ordem = null, $dbwhere = "" ) {

    $sql = "select ";
    if( $campos != "*" ) {

      $campos_sql = split( "#", $campos );
      $virgula    = "";

      for( $i = 0; $i < sizeof( $campos_sql ); $i++ ) {

        $sql     .= $virgula.$campos_sql[$i];
        $virgula  = ",";
      }
    } else {
      $sql .= $campos;
    }

    $sql .= " from regencia ";
    $sql .= "      left join regenciahorario on ed58_i_regencia = ed59_i_codigo";
    $sql2 = "";

    if( $dbwhere == "" ) {

      if( $ed59_i_codigo != null ) {
        $sql2 .= " where regencia.ed59_i_codigo = $ed59_i_codigo ";
      }
    } else if( $dbwhere != "" ) {
      $sql2 = " where $dbwhere";
    }

    $sql .= $sql2;

    if( $ordem != null ) {

      $sql        .= " order by ";
      $campos_sql  = split( "#", $ordem );
      $virgula     = "";

      for( $i = 0 ; $i < sizeof( $campos_sql ); $i++ ) {

        $sql     .= $virgula.$campos_sql[$i];
        $virgula  = ",";
      }
    }

    return $sql;
  }

  public function sql_query_turma_turno ($ed59_i_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") {

    $sql  = "select {$campos}";
    $sql .= "  from regencia ";
    $sql .= "      inner join turma               on turma.ed57_i_codigo               = regencia.ed59_i_turma";
    $sql .= "      inner join turno               on turno.ed15_i_codigo               = turma.ed57_i_turno";
    $sql .= "      inner join periodoescola       on periodoescola.ed17_i_turno        = turno.ed15_i_codigo";
    $sql .= "                                    and periodoescola.ed17_i_escola       = turma.ed57_i_escola";
    $sql .= "      left  join turmaturnoadicional on turmaturnoadicional.ed246_i_turma = turma.ed57_i_codigo";
    $sql2 = "";

    if( empty( $dbwhere ) ) {

      if( !empty( $ed59_i_codigo ) ) {
        $sql2 .= " where regencia.ed59_i_codigo = $ed59_i_codigo ";
      }
    } else if( !empty( $dbwhere ) ) {
      $sql2 = " where $dbwhere";
    }

    $sql .= $sql2;
    if( !empty( $ordem ) ) {
      $sql .= " order by {$ordem}";
    }

    return $sql;
  }
}