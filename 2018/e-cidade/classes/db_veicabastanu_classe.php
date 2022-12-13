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

//MODULO: veiculos
//CLASSE DA ENTIDADE veicabastanu
class cl_veicabastanu { 
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
   var $ve74_codigo = 0; 
   var $ve74_veicabast = 0; 
   var $ve74_motivo = null; 
   var $ve74_data_dia = null; 
   var $ve74_data_mes = null; 
   var $ve74_data_ano = null; 
   var $ve74_data = null; 
   var $ve74_hora = null; 
   var $ve74_usuario = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ve74_codigo = int4 = Código da Anulação 
                 ve74_veicabast = int4 = Abastecimento 
                 ve74_motivo = text = Motivo 
                 ve74_data = date = Data 
                 ve74_hora = char(5) = Hora 
                 ve74_usuario = int4 = Usuário 
                 ";
   //funcao construtor da classe 
   function cl_veicabastanu() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("veicabastanu"); 
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
       $this->ve74_codigo = ($this->ve74_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ve74_codigo"]:$this->ve74_codigo);
       $this->ve74_veicabast = ($this->ve74_veicabast == ""?@$GLOBALS["HTTP_POST_VARS"]["ve74_veicabast"]:$this->ve74_veicabast);
       $this->ve74_motivo = ($this->ve74_motivo == ""?@$GLOBALS["HTTP_POST_VARS"]["ve74_motivo"]:$this->ve74_motivo);
       if($this->ve74_data == ""){
         $this->ve74_data_dia = ($this->ve74_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ve74_data_dia"]:$this->ve74_data_dia);
         $this->ve74_data_mes = ($this->ve74_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ve74_data_mes"]:$this->ve74_data_mes);
         $this->ve74_data_ano = ($this->ve74_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ve74_data_ano"]:$this->ve74_data_ano);
         if($this->ve74_data_dia != ""){
            $this->ve74_data = $this->ve74_data_ano."-".$this->ve74_data_mes."-".$this->ve74_data_dia;
         }
       }
       $this->ve74_hora = ($this->ve74_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["ve74_hora"]:$this->ve74_hora);
       $this->ve74_usuario = ($this->ve74_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["ve74_usuario"]:$this->ve74_usuario);
     }else{
       $this->ve74_codigo = ($this->ve74_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ve74_codigo"]:$this->ve74_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ve74_codigo){ 
      $this->atualizacampos();
     if($this->ve74_veicabast == null ){ 
       $this->erro_sql = " Campo Abastecimento nao Informado.";
       $this->erro_campo = "ve74_veicabast";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve74_motivo == null ){ 
       $this->erro_sql = " Campo Motivo nao Informado.";
       $this->erro_campo = "ve74_motivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve74_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "ve74_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve74_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "ve74_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve74_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "ve74_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ve74_codigo == "" || $ve74_codigo == null ){
       $result = db_query("select nextval('veicabastanu_ve74_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: veicabastanu_ve74_codigo_seq do campo: ve74_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ve74_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from veicabastanu_ve74_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ve74_codigo)){
         $this->erro_sql = " Campo ve74_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ve74_codigo = $ve74_codigo; 
       }
     }
     if(($this->ve74_codigo == null) || ($this->ve74_codigo == "") ){ 
       $this->erro_sql = " Campo ve74_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into veicabastanu(
                                       ve74_codigo 
                                      ,ve74_veicabast 
                                      ,ve74_motivo 
                                      ,ve74_data 
                                      ,ve74_hora 
                                      ,ve74_usuario 
                       )
                values (
                                $this->ve74_codigo 
                               ,$this->ve74_veicabast 
                               ,'$this->ve74_motivo' 
                               ,".($this->ve74_data == "null" || $this->ve74_data == ""?"null":"'".$this->ve74_data."'")." 
                               ,'$this->ve74_hora' 
                               ,$this->ve74_usuario 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Anulação de Abastecimento ($this->ve74_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Anulação de Abastecimento já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Anulação de Abastecimento ($this->ve74_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ve74_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ve74_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9387,'$this->ve74_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1614,9387,'','".AddSlashes(pg_result($resaco,0,'ve74_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1614,9388,'','".AddSlashes(pg_result($resaco,0,'ve74_veicabast'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1614,9389,'','".AddSlashes(pg_result($resaco,0,'ve74_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1614,9390,'','".AddSlashes(pg_result($resaco,0,'ve74_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1614,9391,'','".AddSlashes(pg_result($resaco,0,'ve74_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1614,9392,'','".AddSlashes(pg_result($resaco,0,'ve74_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ve74_codigo=null) { 
      $this->atualizacampos();
     $sql = " update veicabastanu set ";
     $virgula = "";
     if(trim($this->ve74_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve74_codigo"])){ 
       $sql  .= $virgula." ve74_codigo = $this->ve74_codigo ";
       $virgula = ",";
       if(trim($this->ve74_codigo) == null ){ 
         $this->erro_sql = " Campo Código da Anulação nao Informado.";
         $this->erro_campo = "ve74_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve74_veicabast)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve74_veicabast"])){ 
       $sql  .= $virgula." ve74_veicabast = $this->ve74_veicabast ";
       $virgula = ",";
       if(trim($this->ve74_veicabast) == null ){ 
         $this->erro_sql = " Campo Abastecimento nao Informado.";
         $this->erro_campo = "ve74_veicabast";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve74_motivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve74_motivo"])){ 
       $sql  .= $virgula." ve74_motivo = '$this->ve74_motivo' ";
       $virgula = ",";
       if(trim($this->ve74_motivo) == null ){ 
         $this->erro_sql = " Campo Motivo nao Informado.";
         $this->erro_campo = "ve74_motivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve74_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve74_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ve74_data_dia"] !="") ){ 
       $sql  .= $virgula." ve74_data = '$this->ve74_data' ";
       $virgula = ",";
       if(trim($this->ve74_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "ve74_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ve74_data_dia"])){ 
         $sql  .= $virgula." ve74_data = null ";
         $virgula = ",";
         if(trim($this->ve74_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "ve74_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ve74_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve74_hora"])){ 
       $sql  .= $virgula." ve74_hora = '$this->ve74_hora' ";
       $virgula = ",";
       if(trim($this->ve74_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "ve74_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve74_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve74_usuario"])){ 
       $sql  .= $virgula." ve74_usuario = $this->ve74_usuario ";
       $virgula = ",";
       if(trim($this->ve74_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "ve74_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ve74_codigo!=null){
       $sql .= " ve74_codigo = $this->ve74_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ve74_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9387,'$this->ve74_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve74_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1614,9387,'".AddSlashes(pg_result($resaco,$conresaco,'ve74_codigo'))."','$this->ve74_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve74_veicabast"]))
           $resac = db_query("insert into db_acount values($acount,1614,9388,'".AddSlashes(pg_result($resaco,$conresaco,'ve74_veicabast'))."','$this->ve74_veicabast',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve74_motivo"]))
           $resac = db_query("insert into db_acount values($acount,1614,9389,'".AddSlashes(pg_result($resaco,$conresaco,'ve74_motivo'))."','$this->ve74_motivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve74_data"]))
           $resac = db_query("insert into db_acount values($acount,1614,9390,'".AddSlashes(pg_result($resaco,$conresaco,'ve74_data'))."','$this->ve74_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve74_hora"]))
           $resac = db_query("insert into db_acount values($acount,1614,9391,'".AddSlashes(pg_result($resaco,$conresaco,'ve74_hora'))."','$this->ve74_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve74_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1614,9392,'".AddSlashes(pg_result($resaco,$conresaco,'ve74_usuario'))."','$this->ve74_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Anulação de Abastecimento nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ve74_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Anulação de Abastecimento nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ve74_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ve74_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ve74_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ve74_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9387,'$ve74_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1614,9387,'','".AddSlashes(pg_result($resaco,$iresaco,'ve74_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1614,9388,'','".AddSlashes(pg_result($resaco,$iresaco,'ve74_veicabast'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1614,9389,'','".AddSlashes(pg_result($resaco,$iresaco,'ve74_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1614,9390,'','".AddSlashes(pg_result($resaco,$iresaco,'ve74_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1614,9391,'','".AddSlashes(pg_result($resaco,$iresaco,'ve74_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1614,9392,'','".AddSlashes(pg_result($resaco,$iresaco,'ve74_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from veicabastanu
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ve74_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ve74_codigo = $ve74_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Anulação de Abastecimento nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ve74_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Anulação de Abastecimento nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ve74_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ve74_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:veicabastanu";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $ve74_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from veicabastanu ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = veicabastanu.ve74_usuario";
     $sql .= "      inner join veicabast  on  veicabast.ve70_codigo = veicabastanu.ve74_veicabast";
     $sql .= "      inner join db_usuarios as a  on  a.id_usuario = veicabast.ve70_usuario";
     $sql .= "      inner join veiculoscomb on veiculoscomb.ve06_sequencial = veicabast.ve70_veiculoscomb";
     $sql .= "      inner join veiccadcomb  on  veiccadcomb.ve26_codigo = veiculoscomb.ve06_veiccadcomb";
     $sql .= "      inner join veiculos  on  veiculos.ve01_codigo = veicabast.ve70_veiculos";
     $sql2 = "";
     if($dbwhere==""){
       if($ve74_codigo!=null ){
         $sql2 .= " where veicabastanu.ve74_codigo = $ve74_codigo "; 
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
   function sql_query_file ( $ve74_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from veicabastanu ";
     $sql2 = "";
     if($dbwhere==""){
       if($ve74_codigo!=null ){
         $sql2 .= " where veicabastanu.ve74_codigo = $ve74_codigo "; 
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