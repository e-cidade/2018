<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: veiculos
//CLASSE DA ENTIDADE veicmanutencaomedidacancela
class cl_veicmanutencaomedidacancela { 
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
   var $ve67_sequencial = 0; 
   var $ve67_veicmanutencaomedida = 0; 
   var $ve67_usuario = 0; 
   var $ve67_motivo = null; 
   var $ve67_data_dia = null; 
   var $ve67_data_mes = null; 
   var $ve67_data_ano = null; 
   var $ve67_data = null; 
   var $ve67_hora = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ve67_sequencial = int4 = Sequencial 
                 ve67_veicmanutencaomedida = int4 = Manutenção 
                 ve67_usuario = int4 = Usuário 
                 ve67_motivo = text = Motivo 
                 ve67_data = date = Data 
                 ve67_hora = char(5) = Hora 
                 ";
   //funcao construtor da classe 
   function cl_veicmanutencaomedidacancela() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("veicmanutencaomedidacancela"); 
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
       $this->ve67_sequencial = ($this->ve67_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ve67_sequencial"]:$this->ve67_sequencial);
       $this->ve67_veicmanutencaomedida = ($this->ve67_veicmanutencaomedida == ""?@$GLOBALS["HTTP_POST_VARS"]["ve67_veicmanutencaomedida"]:$this->ve67_veicmanutencaomedida);
       $this->ve67_usuario = ($this->ve67_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["ve67_usuario"]:$this->ve67_usuario);
       $this->ve67_motivo = ($this->ve67_motivo == ""?@$GLOBALS["HTTP_POST_VARS"]["ve67_motivo"]:$this->ve67_motivo);
       if($this->ve67_data == ""){
         $this->ve67_data_dia = ($this->ve67_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ve67_data_dia"]:$this->ve67_data_dia);
         $this->ve67_data_mes = ($this->ve67_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ve67_data_mes"]:$this->ve67_data_mes);
         $this->ve67_data_ano = ($this->ve67_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ve67_data_ano"]:$this->ve67_data_ano);
         if($this->ve67_data_dia != ""){
            $this->ve67_data = $this->ve67_data_ano."-".$this->ve67_data_mes."-".$this->ve67_data_dia;
         }
       }
       $this->ve67_hora = ($this->ve67_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["ve67_hora"]:$this->ve67_hora);
     }else{
       $this->ve67_sequencial = ($this->ve67_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ve67_sequencial"]:$this->ve67_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ve67_sequencial){ 
      $this->atualizacampos();
     if($this->ve67_veicmanutencaomedida == null ){ 
       $this->erro_sql = " Campo Manutenção nao Informado.";
       $this->erro_campo = "ve67_veicmanutencaomedida";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve67_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "ve67_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve67_motivo == null ){ 
       $this->erro_sql = " Campo Motivo nao Informado.";
       $this->erro_campo = "ve67_motivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve67_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "ve67_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve67_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "ve67_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ve67_sequencial == "" || $ve67_sequencial == null ){
       $result = db_query("select nextval('veicmanutencaomedidacancela_ve67_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: veicmanutencaomedidacancela_ve67_sequencial_seq do campo: ve67_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ve67_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from veicmanutencaomedidacancela_ve67_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ve67_sequencial)){
         $this->erro_sql = " Campo ve67_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ve67_sequencial = $ve67_sequencial; 
       }
     }
     if(($this->ve67_sequencial == null) || ($this->ve67_sequencial == "") ){ 
       $this->erro_sql = " Campo ve67_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into veicmanutencaomedidacancela(
                                       ve67_sequencial 
                                      ,ve67_veicmanutencaomedida 
                                      ,ve67_usuario 
                                      ,ve67_motivo 
                                      ,ve67_data 
                                      ,ve67_hora 
                       )
                values (
                                $this->ve67_sequencial 
                               ,$this->ve67_veicmanutencaomedida 
                               ,$this->ve67_usuario 
                               ,'$this->ve67_motivo' 
                               ,".($this->ve67_data == "null" || $this->ve67_data == ""?"null":"'".$this->ve67_data."'")." 
                               ,'$this->ve67_hora' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cncelamento Manutenção Medida ($this->ve67_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cncelamento Manutenção Medida já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cncelamento Manutenção Medida ($this->ve67_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ve67_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ve67_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18296,'$this->ve67_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3236,18296,'','".AddSlashes(pg_result($resaco,0,'ve67_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3236,18301,'','".AddSlashes(pg_result($resaco,0,'ve67_veicmanutencaomedida'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3236,18297,'','".AddSlashes(pg_result($resaco,0,'ve67_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3236,18298,'','".AddSlashes(pg_result($resaco,0,'ve67_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3236,18299,'','".AddSlashes(pg_result($resaco,0,'ve67_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3236,18300,'','".AddSlashes(pg_result($resaco,0,'ve67_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ve67_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update veicmanutencaomedidacancela set ";
     $virgula = "";
     if(trim($this->ve67_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve67_sequencial"])){ 
       $sql  .= $virgula." ve67_sequencial = $this->ve67_sequencial ";
       $virgula = ",";
       if(trim($this->ve67_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "ve67_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve67_veicmanutencaomedida)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve67_veicmanutencaomedida"])){ 
       $sql  .= $virgula." ve67_veicmanutencaomedida = $this->ve67_veicmanutencaomedida ";
       $virgula = ",";
       if(trim($this->ve67_veicmanutencaomedida) == null ){ 
         $this->erro_sql = " Campo Manutenção nao Informado.";
         $this->erro_campo = "ve67_veicmanutencaomedida";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve67_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve67_usuario"])){ 
       $sql  .= $virgula." ve67_usuario = $this->ve67_usuario ";
       $virgula = ",";
       if(trim($this->ve67_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "ve67_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve67_motivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve67_motivo"])){ 
       $sql  .= $virgula." ve67_motivo = '$this->ve67_motivo' ";
       $virgula = ",";
       if(trim($this->ve67_motivo) == null ){ 
         $this->erro_sql = " Campo Motivo nao Informado.";
         $this->erro_campo = "ve67_motivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve67_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve67_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ve67_data_dia"] !="") ){ 
       $sql  .= $virgula." ve67_data = '$this->ve67_data' ";
       $virgula = ",";
       if(trim($this->ve67_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "ve67_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ve67_data_dia"])){ 
         $sql  .= $virgula." ve67_data = null ";
         $virgula = ",";
         if(trim($this->ve67_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "ve67_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ve67_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve67_hora"])){ 
       $sql  .= $virgula." ve67_hora = '$this->ve67_hora' ";
       $virgula = ",";
       if(trim($this->ve67_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "ve67_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ve67_sequencial!=null){
       $sql .= " ve67_sequencial = $this->ve67_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ve67_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18296,'$this->ve67_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve67_sequencial"]) || $this->ve67_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3236,18296,'".AddSlashes(pg_result($resaco,$conresaco,'ve67_sequencial'))."','$this->ve67_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve67_veicmanutencaomedida"]) || $this->ve67_veicmanutencaomedida != "")
           $resac = db_query("insert into db_acount values($acount,3236,18301,'".AddSlashes(pg_result($resaco,$conresaco,'ve67_veicmanutencaomedida'))."','$this->ve67_veicmanutencaomedida',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve67_usuario"]) || $this->ve67_usuario != "")
           $resac = db_query("insert into db_acount values($acount,3236,18297,'".AddSlashes(pg_result($resaco,$conresaco,'ve67_usuario'))."','$this->ve67_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve67_motivo"]) || $this->ve67_motivo != "")
           $resac = db_query("insert into db_acount values($acount,3236,18298,'".AddSlashes(pg_result($resaco,$conresaco,'ve67_motivo'))."','$this->ve67_motivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve67_data"]) || $this->ve67_data != "")
           $resac = db_query("insert into db_acount values($acount,3236,18299,'".AddSlashes(pg_result($resaco,$conresaco,'ve67_data'))."','$this->ve67_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve67_hora"]) || $this->ve67_hora != "")
           $resac = db_query("insert into db_acount values($acount,3236,18300,'".AddSlashes(pg_result($resaco,$conresaco,'ve67_hora'))."','$this->ve67_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cncelamento Manutenção Medida nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ve67_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cncelamento Manutenção Medida nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ve67_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ve67_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ve67_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ve67_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18296,'$ve67_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3236,18296,'','".AddSlashes(pg_result($resaco,$iresaco,'ve67_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3236,18301,'','".AddSlashes(pg_result($resaco,$iresaco,'ve67_veicmanutencaomedida'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3236,18297,'','".AddSlashes(pg_result($resaco,$iresaco,'ve67_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3236,18298,'','".AddSlashes(pg_result($resaco,$iresaco,'ve67_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3236,18299,'','".AddSlashes(pg_result($resaco,$iresaco,'ve67_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3236,18300,'','".AddSlashes(pg_result($resaco,$iresaco,'ve67_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from veicmanutencaomedidacancela
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ve67_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ve67_sequencial = $ve67_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cncelamento Manutenção Medida nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ve67_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cncelamento Manutenção Medida nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ve67_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ve67_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:veicmanutencaomedidacancela";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ve67_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from veicmanutencaomedidacancela ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = veicmanutencaomedidacancela.ve67_usuario";
     $sql .= "      inner join veicmanutencaomedida  on  veicmanutencaomedida.ve66_sequencial = veicmanutencaomedidacancela.ve67_veicmanutencaomedida";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = veicmanutencaomedida.ve66_usuario";
     $sql .= "      inner join veiculos  on  veiculos.ve01_codigo = veicmanutencaomedida.ve66_veiculo";
     $sql2 = "";
     if($dbwhere==""){
       if($ve67_sequencial!=null ){
         $sql2 .= " where veicmanutencaomedidacancela.ve67_sequencial = $ve67_sequencial "; 
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
   function sql_query_file ( $ve67_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from veicmanutencaomedidacancela ";
     $sql2 = "";
     if($dbwhere==""){
       if($ve67_sequencial!=null ){
         $sql2 .= " where veicmanutencaomedidacancela.ve67_sequencial = $ve67_sequencial "; 
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