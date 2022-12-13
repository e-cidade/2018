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

//MODULO: transporteescolar
//CLASSE DA ENTIDADE linhatransportepontoparadaaluno
class cl_linhatransportepontoparadaaluno { 
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
   var $tre12_sequencial = 0; 
   var $tre12_linhatransportepontoparada = 0; 
   var $tre12_linhatransportehorarioveiculo = 0; 
   var $tre12_aluno = 0; 
   var $tre12_observacao = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 tre12_sequencial = int4 = Sequencial 
                 tre12_linhatransportepontoparada = int4 = Sequencial 
                 tre12_linhatransportehorarioveiculo = int4 = Horario 
                 tre12_aluno = int8 = Código 
                 tre12_observacao = text = Observação 
                 ";
   //funcao construtor da classe 
   function cl_linhatransportepontoparadaaluno() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("linhatransportepontoparadaaluno"); 
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
       $this->tre12_sequencial = ($this->tre12_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["tre12_sequencial"]:$this->tre12_sequencial);
       $this->tre12_linhatransportepontoparada = ($this->tre12_linhatransportepontoparada == ""?@$GLOBALS["HTTP_POST_VARS"]["tre12_linhatransportepontoparada"]:$this->tre12_linhatransportepontoparada);
       $this->tre12_linhatransportehorarioveiculo = ($this->tre12_linhatransportehorarioveiculo == ""?@$GLOBALS["HTTP_POST_VARS"]["tre12_linhatransportehorarioveiculo"]:$this->tre12_linhatransportehorarioveiculo);
       $this->tre12_aluno = ($this->tre12_aluno == ""?@$GLOBALS["HTTP_POST_VARS"]["tre12_aluno"]:$this->tre12_aluno);
       $this->tre12_observacao = ($this->tre12_observacao == ""?@$GLOBALS["HTTP_POST_VARS"]["tre12_observacao"]:$this->tre12_observacao);
     }else{
       $this->tre12_sequencial = ($this->tre12_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["tre12_sequencial"]:$this->tre12_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($tre12_sequencial){ 
      $this->atualizacampos();
     if($this->tre12_linhatransportepontoparada == null ){ 
       $this->erro_sql = " Campo Sequencial não informado.";
       $this->erro_campo = "tre12_linhatransportepontoparada";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tre12_linhatransportehorarioveiculo == null ){ 
       $this->erro_sql = " Campo Horario não informado.";
       $this->erro_campo = "tre12_linhatransportehorarioveiculo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tre12_aluno == null ){ 
       $this->erro_sql = " Campo Código não informado.";
       $this->erro_campo = "tre12_aluno";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($tre12_sequencial == "" || $tre12_sequencial == null ){
       $result = db_query("select nextval('linhatransportepontoparadaaluno_tre12_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: linhatransportepontoparadaaluno_tre12_sequencial_seq do campo: tre12_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->tre12_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from linhatransportepontoparadaaluno_tre12_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $tre12_sequencial)){
         $this->erro_sql = " Campo tre12_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->tre12_sequencial = $tre12_sequencial; 
       }
     }
     if(($this->tre12_sequencial == null) || ($this->tre12_sequencial == "") ){ 
       $this->erro_sql = " Campo tre12_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into linhatransportepontoparadaaluno(
                                       tre12_sequencial 
                                      ,tre12_linhatransportepontoparada 
                                      ,tre12_linhatransportehorarioveiculo 
                                      ,tre12_aluno 
                                      ,tre12_observacao 
                       )
                values (
                                $this->tre12_sequencial 
                               ,$this->tre12_linhatransportepontoparada 
                               ,$this->tre12_linhatransportehorarioveiculo 
                               ,$this->tre12_aluno 
                               ,'$this->tre12_observacao' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Linha de Transporte Ponto de Parada Aluno ($this->tre12_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Linha de Transporte Ponto de Parada Aluno já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Linha de Transporte Ponto de Parada Aluno ($this->tre12_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->tre12_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->tre12_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20116,'$this->tre12_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3609,20116,'','".AddSlashes(pg_result($resaco,0,'tre12_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3609,20118,'','".AddSlashes(pg_result($resaco,0,'tre12_linhatransportepontoparada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3609,20500,'','".AddSlashes(pg_result($resaco,0,'tre12_linhatransportehorarioveiculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3609,20121,'','".AddSlashes(pg_result($resaco,0,'tre12_aluno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3609,20122,'','".AddSlashes(pg_result($resaco,0,'tre12_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($tre12_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update linhatransportepontoparadaaluno set ";
     $virgula = "";
     if(trim($this->tre12_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tre12_sequencial"])){ 
       $sql  .= $virgula." tre12_sequencial = $this->tre12_sequencial ";
       $virgula = ",";
       if(trim($this->tre12_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "tre12_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tre12_linhatransportepontoparada)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tre12_linhatransportepontoparada"])){ 
       $sql  .= $virgula." tre12_linhatransportepontoparada = $this->tre12_linhatransportepontoparada ";
       $virgula = ",";
       if(trim($this->tre12_linhatransportepontoparada) == null ){ 
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "tre12_linhatransportepontoparada";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tre12_linhatransportehorarioveiculo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tre12_linhatransportehorarioveiculo"])){ 
       $sql  .= $virgula." tre12_linhatransportehorarioveiculo = $this->tre12_linhatransportehorarioveiculo ";
       $virgula = ",";
       if(trim($this->tre12_linhatransportehorarioveiculo) == null ){ 
         $this->erro_sql = " Campo Horario não informado.";
         $this->erro_campo = "tre12_linhatransportehorarioveiculo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tre12_aluno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tre12_aluno"])){ 
       $sql  .= $virgula." tre12_aluno = $this->tre12_aluno ";
       $virgula = ",";
       if(trim($this->tre12_aluno) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "tre12_aluno";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tre12_observacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tre12_observacao"])){ 
       $sql  .= $virgula." tre12_observacao = '$this->tre12_observacao' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($tre12_sequencial!=null){
       $sql .= " tre12_sequencial = $this->tre12_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->tre12_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20116,'$this->tre12_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["tre12_sequencial"]) || $this->tre12_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3609,20116,'".AddSlashes(pg_result($resaco,$conresaco,'tre12_sequencial'))."','$this->tre12_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["tre12_linhatransportepontoparada"]) || $this->tre12_linhatransportepontoparada != "")
             $resac = db_query("insert into db_acount values($acount,3609,20118,'".AddSlashes(pg_result($resaco,$conresaco,'tre12_linhatransportepontoparada'))."','$this->tre12_linhatransportepontoparada',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["tre12_linhatransportehorarioveiculo"]) || $this->tre12_linhatransportehorarioveiculo != "")
             $resac = db_query("insert into db_acount values($acount,3609,20500,'".AddSlashes(pg_result($resaco,$conresaco,'tre12_linhatransportehorarioveiculo'))."','$this->tre12_linhatransportehorarioveiculo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["tre12_aluno"]) || $this->tre12_aluno != "")
             $resac = db_query("insert into db_acount values($acount,3609,20121,'".AddSlashes(pg_result($resaco,$conresaco,'tre12_aluno'))."','$this->tre12_aluno',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["tre12_observacao"]) || $this->tre12_observacao != "")
             $resac = db_query("insert into db_acount values($acount,3609,20122,'".AddSlashes(pg_result($resaco,$conresaco,'tre12_observacao'))."','$this->tre12_observacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Linha de Transporte Ponto de Parada Aluno nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->tre12_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Linha de Transporte Ponto de Parada Aluno nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->tre12_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->tre12_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($tre12_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($tre12_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20116,'$tre12_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3609,20116,'','".AddSlashes(pg_result($resaco,$iresaco,'tre12_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3609,20118,'','".AddSlashes(pg_result($resaco,$iresaco,'tre12_linhatransportepontoparada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3609,20500,'','".AddSlashes(pg_result($resaco,$iresaco,'tre12_linhatransportehorarioveiculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3609,20121,'','".AddSlashes(pg_result($resaco,$iresaco,'tre12_aluno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3609,20122,'','".AddSlashes(pg_result($resaco,$iresaco,'tre12_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from linhatransportepontoparadaaluno
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($tre12_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " tre12_sequencial = $tre12_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Linha de Transporte Ponto de Parada Aluno nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$tre12_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Linha de Transporte Ponto de Parada Aluno nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$tre12_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$tre12_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:linhatransportepontoparadaaluno";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $tre12_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from linhatransportepontoparadaaluno ";
     $sql .= "      inner join linhatransportehorarioveiculo  on  linhatransportehorarioveiculo.tre08_sequencial = linhatransportepontoparadaaluno.tre12_linhatransportehorarioveiculo";
     $sql .= "      inner join linhatransportepontoparada  on  linhatransportepontoparada.tre11_sequencial = linhatransportepontoparadaaluno.tre12_linhatransportepontoparada";
     $sql .= "      inner join aluno  on  aluno.ed47_i_codigo = linhatransportepontoparadaaluno.tre12_aluno";
     $sql .= "      inner join veiculotransportemunicipal  on  veiculotransportemunicipal.tre01_sequencial = linhatransportehorarioveiculo.tre08_veiculotransportemunicipal";
     $sql .= "      inner join linhatransportehorario  on  linhatransportehorario.tre07_sequencial = linhatransportehorarioveiculo.tre08_linhatransportehorario";
     $sql .= "      inner join pontoparada  on  pontoparada.tre04_sequencial = linhatransportepontoparada.tre11_pontoparada";
     $sql .= "      inner join itinerariologradouro  on  itinerariologradouro.tre10_sequencial = linhatransportepontoparada.tre11_itinerariologradouro";
     $sql .= "      inner join pais  on  pais.ed228_i_codigo = aluno.ed47_i_pais";
     $sql .= "      left  join censouf  on  censouf.ed260_i_codigo = aluno.ed47_i_censoufnat and  censouf.ed260_i_codigo = aluno.ed47_i_censoufident and  censouf.ed260_i_codigo = aluno.ed47_i_censoufcert and  censouf.ed260_i_codigo = aluno.ed47_i_censoufend";
     $sql .= "      left  join censomunic  on  censomunic.ed261_i_codigo = aluno.ed47_i_censomunicnat and  censomunic.ed261_i_codigo = aluno.ed47_i_censomunicend and  censomunic.ed261_i_codigo = aluno.ed47_i_censomuniccert";
     $sql .= "      left  join censoorgemissrg  on  censoorgemissrg.ed132_i_codigo = aluno.ed47_i_censoorgemissrg";
     $sql .= "      left  join aluno  as a on   a.ed47_i_codigo = aluno.ed47_i_censocartorio";
     $sql2 = "";
     if($dbwhere==""){
       if($tre12_sequencial!=null ){
         $sql2 .= " where linhatransportepontoparadaaluno.tre12_sequencial = $tre12_sequencial "; 
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
   function sql_query_file ( $tre12_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from linhatransportepontoparadaaluno ";
     $sql2 = "";
     if($dbwhere==""){
       if($tre12_sequencial!=null ){
         $sql2 .= " where linhatransportepontoparadaaluno.tre12_sequencial = $tre12_sequencial "; 
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

  function sql_query_aluno_vinculado( $tre12_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "" ) {

    $sql = "select ";

    if( $campos != "*" ) {

      $campos_sql = split("#",$campos);
      $virgula    = "";

      for( $i = 0; $i < sizeof( $campos_sql ); $i++ ) {

        $sql    .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }

    $sql .= " from linhatransportepontoparadaaluno ";
    $sql .= "      inner join linhatransportehorarioveiculo on linhatransportehorarioveiculo.tre08_sequencial = linhatransportepontoparadaaluno.tre12_linhatransportehorarioveiculo";
    $sql2 = "";

    if( $dbwhere == "" ) {

      if( $tre12_sequencial != null ) {
        $sql2 .= " where linhatransportepontoparadaaluno.tre12_sequencial = $tre12_sequencial ";
      }
    } else if( $dbwhere != "" ) {
      $sql2 = " where $dbwhere";
    }

    $sql .= $sql2;

    if( $ordem != null ) {

      $sql       .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula    = "";

      for( $i = 0; $i < sizeof( $campos_sql ); $i++ ) {

        $sql    .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
}