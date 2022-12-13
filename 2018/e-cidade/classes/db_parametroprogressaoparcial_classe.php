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
//CLASSE DA ENTIDADE parametroprogressaoparcial
class cl_parametroprogressaoparcial {
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
   var $ed112_sequencial = 0;
   var $ed112_formacontrole = 0;
   var $ed112_quantidadedisciplinas = 0;
   var $ed112_habilitado = 'f';
   var $ed112_escola = 0;
   var $ed112_controlefrequencia = 'f';
   var $ed112_disciplinaeliminadependencia = 'f';
   var $ed112_justificativa = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 ed112_sequencial = int4 = Código
                 ed112_formacontrole = int4 = Forma de Controle
                 ed112_quantidadedisciplinas = int4 = Quantidade de Disciplinas Dependentes
                 ed112_habilitado = bool = Habilita Progressão Parcial
                 ed112_escola = int4 = Escola
                 ed112_controlefrequencia = bool = Controle da Frequência
                 ed112_disciplinaeliminadependencia = bool = Disciplina Aprovada Elimina Dependência
                 ed112_justificativa = varchar(40) = Justificativa
                 ";
   //funcao construtor da classe
   function cl_parametroprogressaoparcial() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("parametroprogressaoparcial");
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
       $this->ed112_sequencial = ($this->ed112_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed112_sequencial"]:$this->ed112_sequencial);
       $this->ed112_formacontrole = ($this->ed112_formacontrole == ""?@$GLOBALS["HTTP_POST_VARS"]["ed112_formacontrole"]:$this->ed112_formacontrole);
       $this->ed112_quantidadedisciplinas = ($this->ed112_quantidadedisciplinas == ""?@$GLOBALS["HTTP_POST_VARS"]["ed112_quantidadedisciplinas"]:$this->ed112_quantidadedisciplinas);
       $this->ed112_habilitado = ($this->ed112_habilitado == "f"?@$GLOBALS["HTTP_POST_VARS"]["ed112_habilitado"]:$this->ed112_habilitado);
       $this->ed112_escola = ($this->ed112_escola == ""?@$GLOBALS["HTTP_POST_VARS"]["ed112_escola"]:$this->ed112_escola);
       $this->ed112_controlefrequencia = ($this->ed112_controlefrequencia == "f"?@$GLOBALS["HTTP_POST_VARS"]["ed112_controlefrequencia"]:$this->ed112_controlefrequencia);
       $this->ed112_disciplinaeliminadependencia = ($this->ed112_disciplinaeliminadependencia == "f"?@$GLOBALS["HTTP_POST_VARS"]["ed112_disciplinaeliminadependencia"]:$this->ed112_disciplinaeliminadependencia);
       $this->ed112_justificativa = ($this->ed112_justificativa == "" ? "" : $this->ed112_justificativa);
     }else{
       $this->ed112_sequencial = ($this->ed112_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed112_sequencial"]:$this->ed112_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ed112_sequencial){
      $this->atualizacampos();
     if($this->ed112_formacontrole == null ){
       $this->erro_sql = " Campo Forma de Controle nao Informado.";
       $this->erro_campo = "ed112_formacontrole";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed112_quantidadedisciplinas == null ){
       $this->erro_sql = " Campo Quantidade de Disciplinas Dependentes nao Informado.";
       $this->erro_campo = "ed112_quantidadedisciplinas";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed112_habilitado == null ){
       $this->erro_sql = " Campo Habilita Progressão Parcial nao Informado.";
       $this->erro_campo = "ed112_habilitado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed112_escola == null ){
       $this->erro_sql = " Campo Escola nao Informado.";
       $this->erro_campo = "ed112_escola";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed112_controlefrequencia == null ){
       $this->erro_sql = " Campo Controle da Frequência nao Informado.";
       $this->erro_campo = "ed112_controlefrequencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed112_disciplinaeliminadependencia == null ){
       $this->erro_sql = " Campo Disciplina Aprovada Elimina Dependência nao Informado.";
       $this->erro_campo = "ed112_disciplinaeliminadependencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed112_sequencial == "" || $ed112_sequencial == null ){
       $result = db_query("select nextval('parametroprogressaoparcial_ed112_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: parametroprogressaoparcial_ed112_sequencial_seq do campo: ed112_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->ed112_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from parametroprogressaoparcial_ed112_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed112_sequencial)){
         $this->erro_sql = " Campo ed112_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed112_sequencial = $ed112_sequencial;
       }
     }
     if(($this->ed112_sequencial == null) || ($this->ed112_sequencial == "") ){
       $this->erro_sql = " Campo ed112_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into parametroprogressaoparcial(
                                       ed112_sequencial
                                      ,ed112_formacontrole
                                      ,ed112_quantidadedisciplinas
                                      ,ed112_habilitado
                                      ,ed112_escola
                                      ,ed112_controlefrequencia
                                      ,ed112_disciplinaeliminadependencia
                                      ,ed112_justificativa
                       )
                values (
                                $this->ed112_sequencial
                               ,$this->ed112_formacontrole
                               ,$this->ed112_quantidadedisciplinas
                               ,'$this->ed112_habilitado'
                               ,$this->ed112_escola
                               ,'$this->ed112_controlefrequencia'
                               ,'$this->ed112_disciplinaeliminadependencia'
                               ,'$this->ed112_justificativa'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Parâmetros da progressão parcial ($this->ed112_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Parâmetros da progressão parcial já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Parâmetros da progressão parcial ($this->ed112_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed112_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed112_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,19525,'$this->ed112_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3470,19525,'','".AddSlashes(pg_result($resaco,0,'ed112_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3470,19526,'','".AddSlashes(pg_result($resaco,0,'ed112_formacontrole'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3470,19527,'','".AddSlashes(pg_result($resaco,0,'ed112_quantidadedisciplinas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3470,19528,'','".AddSlashes(pg_result($resaco,0,'ed112_habilitado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3470,19529,'','".AddSlashes(pg_result($resaco,0,'ed112_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3470,19530,'','".AddSlashes(pg_result($resaco,0,'ed112_controlefrequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3470,19531,'','".AddSlashes(pg_result($resaco,0,'ed112_disciplinaeliminadependencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3470,19532,'','".AddSlashes(pg_result($resaco,0,'ed112_justificativa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($ed112_sequencial=null) {
      $this->atualizacampos();
     $sql = " update parametroprogressaoparcial set ";
     $virgula = "";
     if(trim($this->ed112_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed112_sequencial"])){
       $sql  .= $virgula." ed112_sequencial = $this->ed112_sequencial ";
       $virgula = ",";
       if(trim($this->ed112_sequencial) == null ){
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed112_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed112_formacontrole)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed112_formacontrole"])){
       $sql  .= $virgula." ed112_formacontrole = $this->ed112_formacontrole ";
       $virgula = ",";
       if(trim($this->ed112_formacontrole) == null ){
         $this->erro_sql = " Campo Forma de Controle nao Informado.";
         $this->erro_campo = "ed112_formacontrole";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed112_quantidadedisciplinas)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed112_quantidadedisciplinas"])){
       $sql  .= $virgula." ed112_quantidadedisciplinas = $this->ed112_quantidadedisciplinas ";
       $virgula = ",";
       if(trim($this->ed112_quantidadedisciplinas) == null ){
         $this->erro_sql = " Campo Quantidade de Disciplinas Dependentes nao Informado.";
         $this->erro_campo = "ed112_quantidadedisciplinas";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed112_habilitado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed112_habilitado"])){
       $sql  .= $virgula." ed112_habilitado = '$this->ed112_habilitado' ";
       $virgula = ",";
       if(trim($this->ed112_habilitado) == null ){
         $this->erro_sql = " Campo Habilita Progressão Parcial nao Informado.";
         $this->erro_campo = "ed112_habilitado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed112_escola)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed112_escola"])){
       $sql  .= $virgula." ed112_escola = $this->ed112_escola ";
       $virgula = ",";
       if(trim($this->ed112_escola) == null ){
         $this->erro_sql = " Campo Escola nao Informado.";
         $this->erro_campo = "ed112_escola";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed112_controlefrequencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed112_controlefrequencia"])){
       $sql  .= $virgula." ed112_controlefrequencia = '$this->ed112_controlefrequencia' ";
       $virgula = ",";
       if(trim($this->ed112_controlefrequencia) == null ){
         $this->erro_sql = " Campo Controle da Frequência nao Informado.";
         $this->erro_campo = "ed112_controlefrequencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed112_disciplinaeliminadependencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed112_disciplinaeliminadependencia"])){
       $sql  .= $virgula." ed112_disciplinaeliminadependencia = '$this->ed112_disciplinaeliminadependencia' ";
       $virgula = ",";
       if(trim($this->ed112_disciplinaeliminadependencia) == null ){
         $this->erro_sql = " Campo Disciplina Aprovada Elimina Dependência nao Informado.";
         $this->erro_campo = "ed112_disciplinaeliminadependencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }

     if (empty($this->ed112_justificativa)) {
       $sql  .= $virgula." ed112_justificativa = null ";
     } else if(trim($this->ed112_justificativa) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed112_justificativa"])){
       $sql  .= $virgula." ed112_justificativa = '$this->ed112_justificativa' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($ed112_sequencial!=null){
       $sql .= " ed112_sequencial = $this->ed112_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed112_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19525,'$this->ed112_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed112_sequencial"]) || $this->ed112_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3470,19525,'".AddSlashes(pg_result($resaco,$conresaco,'ed112_sequencial'))."','$this->ed112_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed112_formacontrole"]) || $this->ed112_formacontrole != "")
           $resac = db_query("insert into db_acount values($acount,3470,19526,'".AddSlashes(pg_result($resaco,$conresaco,'ed112_formacontrole'))."','$this->ed112_formacontrole',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed112_quantidadedisciplinas"]) || $this->ed112_quantidadedisciplinas != "")
           $resac = db_query("insert into db_acount values($acount,3470,19527,'".AddSlashes(pg_result($resaco,$conresaco,'ed112_quantidadedisciplinas'))."','$this->ed112_quantidadedisciplinas',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed112_habilitado"]) || $this->ed112_habilitado != "")
           $resac = db_query("insert into db_acount values($acount,3470,19528,'".AddSlashes(pg_result($resaco,$conresaco,'ed112_habilitado'))."','$this->ed112_habilitado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed112_escola"]) || $this->ed112_escola != "")
           $resac = db_query("insert into db_acount values($acount,3470,19529,'".AddSlashes(pg_result($resaco,$conresaco,'ed112_escola'))."','$this->ed112_escola',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed112_controlefrequencia"]) || $this->ed112_controlefrequencia != "")
           $resac = db_query("insert into db_acount values($acount,3470,19530,'".AddSlashes(pg_result($resaco,$conresaco,'ed112_controlefrequencia'))."','$this->ed112_controlefrequencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed112_disciplinaeliminadependencia"]) || $this->ed112_disciplinaeliminadependencia != "")
           $resac = db_query("insert into db_acount values($acount,3470,19531,'".AddSlashes(pg_result($resaco,$conresaco,'ed112_disciplinaeliminadependencia'))."','$this->ed112_disciplinaeliminadependencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed112_justificativa"]) || $this->ed112_justificativa != "")
           $resac = db_query("insert into db_acount values($acount,3470,19532,'".AddSlashes(pg_result($resaco,$conresaco,'ed112_justificativa'))."','$this->ed112_justificativa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Parâmetros da progressão parcial nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed112_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Parâmetros da progressão parcial nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed112_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed112_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($ed112_sequencial=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed112_sequencial));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19525,'$ed112_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3470,19525,'','".AddSlashes(pg_result($resaco,$iresaco,'ed112_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3470,19526,'','".AddSlashes(pg_result($resaco,$iresaco,'ed112_formacontrole'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3470,19527,'','".AddSlashes(pg_result($resaco,$iresaco,'ed112_quantidadedisciplinas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3470,19528,'','".AddSlashes(pg_result($resaco,$iresaco,'ed112_habilitado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3470,19529,'','".AddSlashes(pg_result($resaco,$iresaco,'ed112_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3470,19530,'','".AddSlashes(pg_result($resaco,$iresaco,'ed112_controlefrequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3470,19531,'','".AddSlashes(pg_result($resaco,$iresaco,'ed112_disciplinaeliminadependencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3470,19532,'','".AddSlashes(pg_result($resaco,$iresaco,'ed112_justificativa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from parametroprogressaoparcial
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed112_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed112_sequencial = $ed112_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Parâmetros da progressão parcial nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed112_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Parâmetros da progressão parcial nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed112_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed112_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:parametroprogressaoparcial";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
  function sql_query ($ed112_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

    $sql = "select ";
    if ($campos != "*") {
      $campos_sql = split("#", $campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from parametroprogressaoparcial ";
    $sql .= "      inner join escola  on  escola.ed18_i_codigo = parametroprogressaoparcial.ed112_escola";
    $sql .= "      inner join bairro  on  bairro.j13_codi = escola.ed18_i_bairro";
    $sql .= "      inner join ruas  on  ruas.j14_codigo = escola.ed18_i_rua";
    $sql .= "      inner join db_depart  on  db_depart.coddepto = escola.ed18_i_codigo";
    $sql .= "      inner join censouf  on  censouf.ed260_i_codigo = escola.ed18_i_censouf";
    $sql .= "      inner join censomunic  on  censomunic.ed261_i_codigo = escola.ed18_i_censomunic";
    $sql .= "      inner join censodistrito  on  censodistrito.ed262_i_codigo = escola.ed18_i_censodistrito";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($ed112_sequencial != null) {
        $sql2 .= " where parametroprogressaoparcial.ed112_sequencial = $ed112_sequencial ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = split("#", $ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
  // funcao do sql

  function sql_query_file ($ed112_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

    $sql = "select ";
    if ($campos != "*") {
      $campos_sql = split("#", $campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from parametroprogressaoparcial ";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($ed112_sequencial != null) {
        $sql2 .= " where parametroprogressaoparcial.ed112_sequencial = $ed112_sequencial ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = split("#", $ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }

  function sql_query_dados_regencia ($ed112_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

    $sql = "select ";
    if ($campos != "*") {
      $campos_sql = split("#", $campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }

    $sql .= " from parametroprogressaoparcial ";
    $sql .= "      inner join parametroprogressaoparcialetapa  on ed113_parametroprogressaoparcial = ed112_sequencial";
    $sql .= "      inner join serie             on serie.ed11_i_codigo = parametroprogressaoparcialetapa.ed113_serie ";
    $sql .= "      inner join calendarioescola  on calendarioescola.ed38_i_escola = parametroprogressaoparcial.ed112_escola ";
    $sql .= "      inner join calendario        on calendario.ed52_i_codigo  = calendarioescola.ed38_i_calendario ";
    $sql .= "      inner join regencia          on regencia.ed59_i_serie     = serie.ed11_i_codigo       ";
    $sql .= "      inner join turma             on turma.ed57_i_codigo       = regencia.ed59_i_turma     ";
    $sql .= "                                  and turma.ed57_i_calendario   = calendario.ed52_i_codigo  ";
    $sql2 = "";

    if ($dbwhere == "") {
      if ($ed112_sequencial != null) {
        $sql2 .= " where parametroprogressaoparcial.ed112_sequencial = $ed112_sequencial ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = split("#", $ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }

  /* ATENCAO: PLUGIN ParametroProgressaoParcial - SQL sql_query_parametro_dependencia - INSTALADO AQUI - NAO REMOVER */
}