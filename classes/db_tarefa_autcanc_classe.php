<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

//MODULO: atendimento
//CLASSE DA ENTIDADE tarefa_autcanc
class cl_tarefa_autcanc { 
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
   var $at38_tarefaaut = 0; 
   var $at38_usuario = 0; 
   var $at38_data_dia = null; 
   var $at38_data_mes = null; 
   var $at38_data_ano = null; 
   var $at38_data = null; 
   var $at38_hora = null; 
   var $at38_ip = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 at38_tarefaaut = int4 = Código da Autorização cancelada 
                 at38_usuario = int4 = Usuário que cancelou 
                 at38_data = date = Data do cancelamento 
                 at38_hora = char(5) = Hora do cancelamento 
                 at38_ip = varchar(15) = IP 
                 ";
   //funcao construtor da classe 
   function cl_tarefa_autcanc() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("tarefa_autcanc"); 
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
       $this->at38_tarefaaut = ($this->at38_tarefaaut == ""?@$GLOBALS["HTTP_POST_VARS"]["at38_tarefaaut"]:$this->at38_tarefaaut);
       $this->at38_usuario = ($this->at38_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["at38_usuario"]:$this->at38_usuario);
       if($this->at38_data == ""){
         $this->at38_data_dia = ($this->at38_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["at38_data_dia"]:$this->at38_data_dia);
         $this->at38_data_mes = ($this->at38_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["at38_data_mes"]:$this->at38_data_mes);
         $this->at38_data_ano = ($this->at38_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["at38_data_ano"]:$this->at38_data_ano);
         if($this->at38_data_dia != ""){
            $this->at38_data = $this->at38_data_ano."-".$this->at38_data_mes."-".$this->at38_data_dia;
         }
       }
       $this->at38_hora = ($this->at38_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["at38_hora"]:$this->at38_hora);
       $this->at38_ip = ($this->at38_ip == ""?@$GLOBALS["HTTP_POST_VARS"]["at38_ip"]:$this->at38_ip);
     }else{
       $this->at38_tarefaaut = ($this->at38_tarefaaut == ""?@$GLOBALS["HTTP_POST_VARS"]["at38_tarefaaut"]:$this->at38_tarefaaut);
     }
   }
   // funcao para inclusao
   function incluir ($at38_tarefaaut){ 
      $this->atualizacampos();
     if($this->at38_usuario == null ){ 
       $this->erro_sql = " Campo Usuário que cancelou nao Informado.";
       $this->erro_campo = "at38_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at38_data == null ){ 
       $this->erro_sql = " Campo Data do cancelamento nao Informado.";
       $this->erro_campo = "at38_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at38_hora == null ){ 
       $this->erro_sql = " Campo Hora do cancelamento nao Informado.";
       $this->erro_campo = "at38_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at38_ip == null ){ 
       $this->erro_sql = " Campo IP nao Informado.";
       $this->erro_campo = "at38_ip";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->at38_tarefaaut = $at38_tarefaaut; 
     if(($this->at38_tarefaaut == null) || ($this->at38_tarefaaut == "") ){ 
       $this->erro_sql = " Campo at38_tarefaaut nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into tarefa_autcanc(
                                       at38_tarefaaut 
                                      ,at38_usuario 
                                      ,at38_data 
                                      ,at38_hora 
                                      ,at38_ip 
                       )
                values (
                                $this->at38_tarefaaut 
                               ,$this->at38_usuario 
                               ,".($this->at38_data == "null" || $this->at38_data == ""?"null":"'".$this->at38_data."'")." 
                               ,'$this->at38_hora' 
                               ,'$this->at38_ip' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Autorizações canceladas ($this->at38_tarefaaut) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Autorizações canceladas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Autorizações canceladas ($this->at38_tarefaaut) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->at38_tarefaaut;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->at38_tarefaaut));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8821,'$this->at38_tarefaaut','I')");
       $resac = db_query("insert into db_acount values($acount,1506,8821,'','".AddSlashes(pg_result($resaco,0,'at38_tarefaaut'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1506,8822,'','".AddSlashes(pg_result($resaco,0,'at38_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1506,8823,'','".AddSlashes(pg_result($resaco,0,'at38_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1506,8824,'','".AddSlashes(pg_result($resaco,0,'at38_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1506,8825,'','".AddSlashes(pg_result($resaco,0,'at38_ip'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($at38_tarefaaut=null) { 
      $this->atualizacampos();
     $sql = " update tarefa_autcanc set ";
     $virgula = "";
     if(trim($this->at38_tarefaaut)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at38_tarefaaut"])){ 
       $sql  .= $virgula." at38_tarefaaut = $this->at38_tarefaaut ";
       $virgula = ",";
       if(trim($this->at38_tarefaaut) == null ){ 
         $this->erro_sql = " Campo Código da Autorização cancelada nao Informado.";
         $this->erro_campo = "at38_tarefaaut";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at38_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at38_usuario"])){ 
       $sql  .= $virgula." at38_usuario = $this->at38_usuario ";
       $virgula = ",";
       if(trim($this->at38_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário que cancelou nao Informado.";
         $this->erro_campo = "at38_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at38_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at38_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["at38_data_dia"] !="") ){ 
       $sql  .= $virgula." at38_data = '$this->at38_data' ";
       $virgula = ",";
       if(trim($this->at38_data) == null ){ 
         $this->erro_sql = " Campo Data do cancelamento nao Informado.";
         $this->erro_campo = "at38_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["at38_data_dia"])){ 
         $sql  .= $virgula." at38_data = null ";
         $virgula = ",";
         if(trim($this->at38_data) == null ){ 
           $this->erro_sql = " Campo Data do cancelamento nao Informado.";
           $this->erro_campo = "at38_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->at38_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at38_hora"])){ 
       $sql  .= $virgula." at38_hora = '$this->at38_hora' ";
       $virgula = ",";
       if(trim($this->at38_hora) == null ){ 
         $this->erro_sql = " Campo Hora do cancelamento nao Informado.";
         $this->erro_campo = "at38_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at38_ip)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at38_ip"])){ 
       $sql  .= $virgula." at38_ip = '$this->at38_ip' ";
       $virgula = ",";
       if(trim($this->at38_ip) == null ){ 
         $this->erro_sql = " Campo IP nao Informado.";
         $this->erro_campo = "at38_ip";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($at38_tarefaaut!=null){
       $sql .= " at38_tarefaaut = $this->at38_tarefaaut";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->at38_tarefaaut));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8821,'$this->at38_tarefaaut','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at38_tarefaaut"]))
           $resac = db_query("insert into db_acount values($acount,1506,8821,'".AddSlashes(pg_result($resaco,$conresaco,'at38_tarefaaut'))."','$this->at38_tarefaaut',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at38_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1506,8822,'".AddSlashes(pg_result($resaco,$conresaco,'at38_usuario'))."','$this->at38_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at38_data"]))
           $resac = db_query("insert into db_acount values($acount,1506,8823,'".AddSlashes(pg_result($resaco,$conresaco,'at38_data'))."','$this->at38_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at38_hora"]))
           $resac = db_query("insert into db_acount values($acount,1506,8824,'".AddSlashes(pg_result($resaco,$conresaco,'at38_hora'))."','$this->at38_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at38_ip"]))
           $resac = db_query("insert into db_acount values($acount,1506,8825,'".AddSlashes(pg_result($resaco,$conresaco,'at38_ip'))."','$this->at38_ip',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Autorizações canceladas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->at38_tarefaaut;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Autorizações canceladas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->at38_tarefaaut;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->at38_tarefaaut;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($at38_tarefaaut=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($at38_tarefaaut));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8821,'$at38_tarefaaut','E')");
         $resac = db_query("insert into db_acount values($acount,1506,8821,'','".AddSlashes(pg_result($resaco,$iresaco,'at38_tarefaaut'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1506,8822,'','".AddSlashes(pg_result($resaco,$iresaco,'at38_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1506,8823,'','".AddSlashes(pg_result($resaco,$iresaco,'at38_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1506,8824,'','".AddSlashes(pg_result($resaco,$iresaco,'at38_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1506,8825,'','".AddSlashes(pg_result($resaco,$iresaco,'at38_ip'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from tarefa_autcanc
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($at38_tarefaaut != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " at38_tarefaaut = $at38_tarefaaut ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Autorizações canceladas nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$at38_tarefaaut;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Autorizações canceladas nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$at38_tarefaaut;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$at38_tarefaaut;
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
        $this->erro_sql   = "Record Vazio na Tabela:tarefa_autcanc";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $at38_tarefaaut=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tarefa_autcanc ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = tarefa_autcanc.at38_usuario";
     $sql .= "      inner join tarefa_aut  on  tarefa_aut.at39_sequencia = tarefa_autcanc.at38_tarefaaut";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = tarefa_aut.at39_usuario";
     $sql .= "      inner join tarefa  as a on   a.at40_sequencial = tarefa_aut.at39_tarefa";
     $sql2 = "";
     if($dbwhere==""){
       if($at38_tarefaaut!=null ){
         $sql2 .= " where tarefa_autcanc.at38_tarefaaut = $at38_tarefaaut "; 
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
   function sql_query_file ( $at38_tarefaaut=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tarefa_autcanc ";
     $sql2 = "";
     if($dbwhere==""){
       if($at38_tarefaaut!=null ){
         $sql2 .= " where tarefa_autcanc.at38_tarefaaut = $at38_tarefaaut "; 
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