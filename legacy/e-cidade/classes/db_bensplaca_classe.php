<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: patrim
//CLASSE DA ENTIDADE bensplaca
class cl_bensplaca { 
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
   var $t41_codigo = 0; 
   var $t41_bem = 0; 
   var $t41_placa = null; 
   var $t41_placaseq = 0; 
   var $t41_obs = null; 
   var $t41_data_dia = null; 
   var $t41_data_mes = null; 
   var $t41_data_ano = null; 
   var $t41_data = null; 
   var $t41_usuario = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 t41_codigo = int4 = Código 
                 t41_bem = int8 = Código do bem 
                 t41_placa = varchar(20) = Placa 
                 t41_placaseq = int4 = Nº sequencial da placa 
                 t41_obs = text = Observação referente a placa 
                 t41_data = date = Data Placa 
                 t41_usuario = int4 = Cod. Usuário 
                 ";
   //funcao construtor da classe 
   function cl_bensplaca() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("bensplaca"); 
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
       $this->t41_codigo = ($this->t41_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["t41_codigo"]:$this->t41_codigo);
       $this->t41_bem = ($this->t41_bem == ""?@$GLOBALS["HTTP_POST_VARS"]["t41_bem"]:$this->t41_bem);
       $this->t41_placa = ($this->t41_placa == ""?@$GLOBALS["HTTP_POST_VARS"]["t41_placa"]:$this->t41_placa);
       $this->t41_placaseq = ($this->t41_placaseq == ""?@$GLOBALS["HTTP_POST_VARS"]["t41_placaseq"]:$this->t41_placaseq);
       $this->t41_obs = ($this->t41_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["t41_obs"]:$this->t41_obs);
       if($this->t41_data == ""){
         $this->t41_data_dia = ($this->t41_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["t41_data_dia"]:$this->t41_data_dia);
         $this->t41_data_mes = ($this->t41_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["t41_data_mes"]:$this->t41_data_mes);
         $this->t41_data_ano = ($this->t41_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["t41_data_ano"]:$this->t41_data_ano);
         if($this->t41_data_dia != ""){
            $this->t41_data = $this->t41_data_ano."-".$this->t41_data_mes."-".$this->t41_data_dia;
         }
       }
       $this->t41_usuario = ($this->t41_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["t41_usuario"]:$this->t41_usuario);
     }else{
       $this->t41_codigo = ($this->t41_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["t41_codigo"]:$this->t41_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($t41_codigo){ 
      $this->atualizacampos();
     if($this->t41_bem == null ){ 
       $this->erro_sql = " Campo Código do bem nao Informado.";
       $this->erro_campo = "t41_bem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t41_placaseq == null ){ 
       $this->erro_sql = " Campo Nº sequencial da placa nao Informado.";
       $this->erro_campo = "t41_placaseq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t41_data == null ){ 
       $this->erro_sql = " Campo Data Placa nao Informado.";
       $this->erro_campo = "t41_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t41_usuario == null ){ 
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "t41_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($t41_codigo == "" || $t41_codigo == null ){
       $result = db_query("select nextval('bensplaca_t41_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: bensplaca_t41_codigo_seq do campo: t41_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->t41_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from bensplaca_t41_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $t41_codigo)){
         $this->erro_sql = " Campo t41_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->t41_codigo = $t41_codigo; 
       }
     }
     if(($this->t41_codigo == null) || ($this->t41_codigo == "") ){ 
       $this->erro_sql = " Campo t41_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into bensplaca(
                                       t41_codigo 
                                      ,t41_bem 
                                      ,t41_placa 
                                      ,t41_placaseq 
                                      ,t41_obs 
                                      ,t41_data 
                                      ,t41_usuario 
                       )
                values (
                                $this->t41_codigo 
                               ,$this->t41_bem 
                               ,'$this->t41_placa' 
                               ,$this->t41_placaseq 
                               ,'$this->t41_obs' 
                               ,".($this->t41_data == "null" || $this->t41_data == ""?"null":"'".$this->t41_data."'")." 
                               ,$this->t41_usuario 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Placa referente a um bem. ($this->t41_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Placa referente a um bem. já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Placa referente a um bem. ($this->t41_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t41_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->t41_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8905,'$this->t41_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1523,8905,'','".AddSlashes(pg_result($resaco,0,'t41_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1523,8906,'','".AddSlashes(pg_result($resaco,0,'t41_bem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1523,8907,'','".AddSlashes(pg_result($resaco,0,'t41_placa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1523,8908,'','".AddSlashes(pg_result($resaco,0,'t41_placaseq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1523,8909,'','".AddSlashes(pg_result($resaco,0,'t41_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1523,8910,'','".AddSlashes(pg_result($resaco,0,'t41_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1523,8911,'','".AddSlashes(pg_result($resaco,0,'t41_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($t41_codigo=null) { 
      $this->atualizacampos();
     $sql = " update bensplaca set ";
     $virgula = "";
     if(trim($this->t41_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t41_codigo"])){ 
       $sql  .= $virgula." t41_codigo = $this->t41_codigo ";
       $virgula = ",";
       if(trim($this->t41_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "t41_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t41_bem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t41_bem"])){ 
       $sql  .= $virgula." t41_bem = $this->t41_bem ";
       $virgula = ",";
       if(trim($this->t41_bem) == null ){ 
         $this->erro_sql = " Campo Código do bem nao Informado.";
         $this->erro_campo = "t41_bem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t41_placa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t41_placa"])){ 
       $sql  .= $virgula." t41_placa = '$this->t41_placa' ";
       $virgula = ",";
     }
     if(trim($this->t41_placaseq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t41_placaseq"])){ 
       $sql  .= $virgula." t41_placaseq = $this->t41_placaseq ";
       $virgula = ",";
       if(trim($this->t41_placaseq) == null ){ 
         $this->erro_sql = " Campo Nº sequencial da placa nao Informado.";
         $this->erro_campo = "t41_placaseq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t41_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t41_obs"])){ 
       $sql  .= $virgula." t41_obs = '$this->t41_obs' ";
       $virgula = ",";
     }
     if(trim($this->t41_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t41_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["t41_data_dia"] !="") ){ 
       $sql  .= $virgula." t41_data = '$this->t41_data' ";
       $virgula = ",";
       if(trim($this->t41_data) == null ){ 
         $this->erro_sql = " Campo Data Placa nao Informado.";
         $this->erro_campo = "t41_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["t41_data_dia"])){ 
         $sql  .= $virgula." t41_data = null ";
         $virgula = ",";
         if(trim($this->t41_data) == null ){ 
           $this->erro_sql = " Campo Data Placa nao Informado.";
           $this->erro_campo = "t41_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->t41_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t41_usuario"])){ 
       $sql  .= $virgula." t41_usuario = $this->t41_usuario ";
       $virgula = ",";
       if(trim($this->t41_usuario) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "t41_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($t41_codigo!=null){
       $sql .= " t41_codigo = $this->t41_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->t41_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8905,'$this->t41_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t41_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1523,8905,'".AddSlashes(pg_result($resaco,$conresaco,'t41_codigo'))."','$this->t41_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t41_bem"]))
           $resac = db_query("insert into db_acount values($acount,1523,8906,'".AddSlashes(pg_result($resaco,$conresaco,'t41_bem'))."','$this->t41_bem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t41_placa"]))
           $resac = db_query("insert into db_acount values($acount,1523,8907,'".AddSlashes(pg_result($resaco,$conresaco,'t41_placa'))."','$this->t41_placa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t41_placaseq"]))
           $resac = db_query("insert into db_acount values($acount,1523,8908,'".AddSlashes(pg_result($resaco,$conresaco,'t41_placaseq'))."','$this->t41_placaseq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t41_obs"]))
           $resac = db_query("insert into db_acount values($acount,1523,8909,'".AddSlashes(pg_result($resaco,$conresaco,'t41_obs'))."','$this->t41_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t41_data"]))
           $resac = db_query("insert into db_acount values($acount,1523,8910,'".AddSlashes(pg_result($resaco,$conresaco,'t41_data'))."','$this->t41_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t41_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1523,8911,'".AddSlashes(pg_result($resaco,$conresaco,'t41_usuario'))."','$this->t41_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Placa referente a um bem. nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->t41_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Placa referente a um bem. nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->t41_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t41_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($t41_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($t41_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8905,'$t41_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1523,8905,'','".AddSlashes(pg_result($resaco,$iresaco,'t41_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1523,8906,'','".AddSlashes(pg_result($resaco,$iresaco,'t41_bem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1523,8907,'','".AddSlashes(pg_result($resaco,$iresaco,'t41_placa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1523,8908,'','".AddSlashes(pg_result($resaco,$iresaco,'t41_placaseq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1523,8909,'','".AddSlashes(pg_result($resaco,$iresaco,'t41_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1523,8910,'','".AddSlashes(pg_result($resaco,$iresaco,'t41_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1523,8911,'','".AddSlashes(pg_result($resaco,$iresaco,'t41_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from bensplaca
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($t41_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " t41_codigo = $t41_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Placa referente a um bem. nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$t41_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Placa referente a um bem. nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$t41_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$t41_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:bensplaca";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $t41_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from bensplaca ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = bensplaca.t41_usuario";
     $sql .= "      inner join bens  on  bens.t52_bem = bensplaca.t41_bem";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = bens.t52_numcgm";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = bens.t52_depart";
     $sql .= "      inner join clabens  on  clabens.t64_codcla = bens.t52_codcla";
     $sql2 = "";
     if($dbwhere==""){
       if($t41_codigo!=null ){
         $sql2 .= " where bensplaca.t41_codigo = $t41_codigo "; 
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
   function sql_query_file ( $t41_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from bensplaca ";
     $sql2 = "";
     if($dbwhere==""){
       if($t41_codigo!=null ){
         $sql2 .= " where bensplaca.t41_codigo = $t41_codigo "; 
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
   /**
    * Seleciona um item e da um lock na tabela para novos updates 
    * @return string
    */
   function sql_query_fileLockInLine ($t41_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
     $sql = "select ";
     if($campos != "*" ) {
       
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++) {
         
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else {
       $sql .= $campos;
     }
     
     $sql .= " from bensplaca ";
     $sql .= "      inner join bens on bens.t52_bem = bensplaca.t41_bem ";
     
     $sql2 = "";
     if($dbwhere=="") {
       
       if($t41_codigo!=null ){
         $sql2 .= " where bensplaca.t41_codigo = $t41_codigo ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ) {
       
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++) {
         
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
   }   
  
  /**
   * Busca as placas do Bem
   * @param intger $t41_codigo
   * @param string $campos
   * @param string $ordem
   * @param string $dbwhere
   * @return string
   */
  function sql_query_placa_bem($t41_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {
  	
   	$sql = "select ";
   	if ($campos != "*" ) {
   		
   		$campos_sql = split("#",$campos);
   		$virgula    = "";
   		
   		for ($i = 0;$i < sizeof($campos_sql); $i++) {
   			
   			$sql    .= $virgula.$campos_sql[$i];
   			$virgula = ",";
   		}
   	} else {
   		$sql .= $campos;
   	}
   	$sql .= " from bensplaca ";
   	$sql .= "      inner join bens  on  bens.t52_bem = bensplaca.t41_bem";
   	$sql2 = "";
   	if ($dbwhere == "") {
   		
   		if ($t41_codigo != null) {
   			$sql2 .= " where bensplaca.t41_codigo = $t41_codigo ";
   		}
 		} else if ($dbwhere != "") {
   		$sql2 = " where $dbwhere";
   	}
   	$sql .= $sql2;
   	if ($ordem != null) {
   		
   		$sql       .= " order by ";
   		$campos_sql = split("#",$ordem);
   		$virgula    = "";
   		
   		for ($i = 0; $i < sizeof($campos_sql); $i++) {
   			
   			$sql .= $virgula.$campos_sql[$i];
   			$virgula = ",";
   		}
   	}
   	return $sql;
 	}
}
?>