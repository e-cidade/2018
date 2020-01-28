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

//MODULO: juridico
//CLASSE DA ENTIDADE inicial
class cl_inicial { 
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
   var $v50_inicial = 0; 
   var $v50_advog = 0; 
   var $v50_data_dia = null; 
   var $v50_data_mes = null; 
   var $v50_data_ano = null; 
   var $v50_data = null; 
   var $v50_id_login = 0; 
   var $v50_codlocal = 0; 
   var $v50_codmov = 0; 
   var $v50_instit = 0; 
   var $v50_situacao = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 v50_inicial = int4 = Inicial Numero 
                 v50_advog = int4 = Advogado 
                 v50_data = date = Data Inicial 
                 v50_id_login = int4 = Código Usuário 
                 v50_codlocal = int4 = Local Foro 
                 v50_codmov = int4 = Movimento 
                 v50_instit = int4 = Cod. Instituição 
                 v50_situacao = int4 = Situação 
                 ";
   //funcao construtor da classe 
   function cl_inicial() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("inicial"); 
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
       $this->v50_inicial = ($this->v50_inicial == ""?@$GLOBALS["HTTP_POST_VARS"]["v50_inicial"]:$this->v50_inicial);
       $this->v50_advog = ($this->v50_advog == ""?@$GLOBALS["HTTP_POST_VARS"]["v50_advog"]:$this->v50_advog);
       if($this->v50_data == ""){
         $this->v50_data_dia = ($this->v50_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["v50_data_dia"]:$this->v50_data_dia);
         $this->v50_data_mes = ($this->v50_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["v50_data_mes"]:$this->v50_data_mes);
         $this->v50_data_ano = ($this->v50_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["v50_data_ano"]:$this->v50_data_ano);
         if($this->v50_data_dia != ""){
            $this->v50_data = $this->v50_data_ano."-".$this->v50_data_mes."-".$this->v50_data_dia;
         }
       }
       $this->v50_id_login = ($this->v50_id_login == ""?@$GLOBALS["HTTP_POST_VARS"]["v50_id_login"]:$this->v50_id_login);
       $this->v50_codlocal = ($this->v50_codlocal == ""?@$GLOBALS["HTTP_POST_VARS"]["v50_codlocal"]:$this->v50_codlocal);
       $this->v50_codmov = ($this->v50_codmov == ""?@$GLOBALS["HTTP_POST_VARS"]["v50_codmov"]:$this->v50_codmov);
       $this->v50_instit = ($this->v50_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["v50_instit"]:$this->v50_instit);
       $this->v50_situacao = ($this->v50_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["v50_situacao"]:$this->v50_situacao);
     }else{
       $this->v50_inicial = ($this->v50_inicial == ""?@$GLOBALS["HTTP_POST_VARS"]["v50_inicial"]:$this->v50_inicial);
     }
   }
   // funcao para inclusao
   function incluir ($v50_inicial){ 
      $this->atualizacampos();
     if($this->v50_advog == null ){ 
       $this->erro_sql = " Campo Advogado nao Informado.";
       $this->erro_campo = "v50_advog";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v50_data == null ){ 
       $this->erro_sql = " Campo Data Inicial nao Informado.";
       $this->erro_campo = "v50_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v50_id_login == null ){ 
       $this->erro_sql = " Campo Código Usuário nao Informado.";
       $this->erro_campo = "v50_id_login";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v50_codlocal == null ){ 
       $this->erro_sql = " Campo Local Foro nao Informado.";
       $this->erro_campo = "v50_codlocal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v50_codmov == null ){ 
       $this->erro_sql = " Campo Movimento nao Informado.";
       $this->erro_campo = "v50_codmov";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v50_instit == null ){ 
       $this->erro_sql = " Campo Cod. Instituição nao Informado.";
       $this->erro_campo = "v50_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v50_situacao == null ){ 
       $this->erro_sql = " Campo Situação nao Informado.";
       $this->erro_campo = "v50_situacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($v50_inicial == "" || $v50_inicial == null ){
       $result = db_query("select nextval('inicial_v50_inicial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: inicial_v50_inicial_seq do campo: v50_inicial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->v50_inicial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from inicial_v50_inicial_seq");
       if(($result != false) && (pg_result($result,0,0) < $v50_inicial)){
         $this->erro_sql = " Campo v50_inicial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->v50_inicial = $v50_inicial; 
       }
     }
     if(($this->v50_inicial == null) || ($this->v50_inicial == "") ){ 
       $this->erro_sql = " Campo v50_inicial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into inicial(
                                       v50_inicial 
                                      ,v50_advog 
                                      ,v50_data 
                                      ,v50_id_login 
                                      ,v50_codlocal 
                                      ,v50_codmov 
                                      ,v50_instit 
                                      ,v50_situacao 
                       )
                values (
                                $this->v50_inicial 
                               ,$this->v50_advog 
                               ,".($this->v50_data == "null" || $this->v50_data == ""?"null":"'".$this->v50_data."'")." 
                               ,$this->v50_id_login 
                               ,$this->v50_codlocal 
                               ,$this->v50_codmov 
                               ,$this->v50_instit 
                               ,$this->v50_situacao 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->v50_inicial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->v50_inicial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v50_inicial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->v50_inicial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,564,'$this->v50_inicial','I')");
       $resac = db_query("insert into db_acount values($acount,108,564,'','".AddSlashes(pg_result($resaco,0,'v50_inicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,108,2559,'','".AddSlashes(pg_result($resaco,0,'v50_advog'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,108,2561,'','".AddSlashes(pg_result($resaco,0,'v50_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,108,2562,'','".AddSlashes(pg_result($resaco,0,'v50_id_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,108,2563,'','".AddSlashes(pg_result($resaco,0,'v50_codlocal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,108,2566,'','".AddSlashes(pg_result($resaco,0,'v50_codmov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,108,10651,'','".AddSlashes(pg_result($resaco,0,'v50_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,108,11063,'','".AddSlashes(pg_result($resaco,0,'v50_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($v50_inicial=null) { 
      $this->atualizacampos();
     $sql = " update inicial set ";
     $virgula = "";
     if(trim($this->v50_inicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v50_inicial"])){ 
       $sql  .= $virgula." v50_inicial = $this->v50_inicial ";
       $virgula = ",";
       if(trim($this->v50_inicial) == null ){ 
         $this->erro_sql = " Campo Inicial Numero nao Informado.";
         $this->erro_campo = "v50_inicial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v50_advog)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v50_advog"])){ 
       $sql  .= $virgula." v50_advog = $this->v50_advog ";
       $virgula = ",";
       if(trim($this->v50_advog) == null ){ 
         $this->erro_sql = " Campo Advogado nao Informado.";
         $this->erro_campo = "v50_advog";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v50_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v50_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["v50_data_dia"] !="") ){ 
       $sql  .= $virgula." v50_data = '$this->v50_data' ";
       $virgula = ",";
       if(trim($this->v50_data) == null ){ 
         $this->erro_sql = " Campo Data Inicial nao Informado.";
         $this->erro_campo = "v50_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["v50_data_dia"])){ 
         $sql  .= $virgula." v50_data = null ";
         $virgula = ",";
         if(trim($this->v50_data) == null ){ 
           $this->erro_sql = " Campo Data Inicial nao Informado.";
           $this->erro_campo = "v50_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->v50_id_login)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v50_id_login"])){ 
       $sql  .= $virgula." v50_id_login = $this->v50_id_login ";
       $virgula = ",";
       if(trim($this->v50_id_login) == null ){ 
         $this->erro_sql = " Campo Código Usuário nao Informado.";
         $this->erro_campo = "v50_id_login";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v50_codlocal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v50_codlocal"])){ 
       $sql  .= $virgula." v50_codlocal = $this->v50_codlocal ";
       $virgula = ",";
       if(trim($this->v50_codlocal) == null ){ 
         $this->erro_sql = " Campo Local Foro nao Informado.";
         $this->erro_campo = "v50_codlocal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v50_codmov)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v50_codmov"])){ 
       $sql  .= $virgula." v50_codmov = $this->v50_codmov ";
       $virgula = ",";
       if(trim($this->v50_codmov) == null ){ 
         $this->erro_sql = " Campo Movimento nao Informado.";
         $this->erro_campo = "v50_codmov";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v50_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v50_instit"])){ 
       $sql  .= $virgula." v50_instit = $this->v50_instit ";
       $virgula = ",";
       if(trim($this->v50_instit) == null ){ 
         $this->erro_sql = " Campo Cod. Instituição nao Informado.";
         $this->erro_campo = "v50_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v50_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v50_situacao"])){ 
       $sql  .= $virgula." v50_situacao = $this->v50_situacao ";
       $virgula = ",";
       if(trim($this->v50_situacao) == null ){ 
         $this->erro_sql = " Campo Situação nao Informado.";
         $this->erro_campo = "v50_situacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($v50_inicial!=null){
       $sql .= " v50_inicial = $this->v50_inicial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->v50_inicial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,564,'$this->v50_inicial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v50_inicial"]) || $this->v50_inicial != "")
           $resac = db_query("insert into db_acount values($acount,108,564,'".AddSlashes(pg_result($resaco,$conresaco,'v50_inicial'))."','$this->v50_inicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v50_advog"]) || $this->v50_advog != "")
           $resac = db_query("insert into db_acount values($acount,108,2559,'".AddSlashes(pg_result($resaco,$conresaco,'v50_advog'))."','$this->v50_advog',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v50_data"]) || $this->v50_data != "")
           $resac = db_query("insert into db_acount values($acount,108,2561,'".AddSlashes(pg_result($resaco,$conresaco,'v50_data'))."','$this->v50_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v50_id_login"]) || $this->v50_id_login != "")
           $resac = db_query("insert into db_acount values($acount,108,2562,'".AddSlashes(pg_result($resaco,$conresaco,'v50_id_login'))."','$this->v50_id_login',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v50_codlocal"]) || $this->v50_codlocal != "")
           $resac = db_query("insert into db_acount values($acount,108,2563,'".AddSlashes(pg_result($resaco,$conresaco,'v50_codlocal'))."','$this->v50_codlocal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v50_codmov"]) || $this->v50_codmov != "")
           $resac = db_query("insert into db_acount values($acount,108,2566,'".AddSlashes(pg_result($resaco,$conresaco,'v50_codmov'))."','$this->v50_codmov',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v50_instit"]) || $this->v50_instit != "")
           $resac = db_query("insert into db_acount values($acount,108,10651,'".AddSlashes(pg_result($resaco,$conresaco,'v50_instit'))."','$this->v50_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v50_situacao"]) || $this->v50_situacao != "")
           $resac = db_query("insert into db_acount values($acount,108,11063,'".AddSlashes(pg_result($resaco,$conresaco,'v50_situacao'))."','$this->v50_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->v50_inicial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->v50_inicial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v50_inicial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($v50_inicial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($v50_inicial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,564,'$v50_inicial','E')");
         $resac = db_query("insert into db_acount values($acount,108,564,'','".AddSlashes(pg_result($resaco,$iresaco,'v50_inicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,108,2559,'','".AddSlashes(pg_result($resaco,$iresaco,'v50_advog'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,108,2561,'','".AddSlashes(pg_result($resaco,$iresaco,'v50_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,108,2562,'','".AddSlashes(pg_result($resaco,$iresaco,'v50_id_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,108,2563,'','".AddSlashes(pg_result($resaco,$iresaco,'v50_codlocal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,108,2566,'','".AddSlashes(pg_result($resaco,$iresaco,'v50_codmov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,108,10651,'','".AddSlashes(pg_result($resaco,$iresaco,'v50_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,108,11063,'','".AddSlashes(pg_result($resaco,$iresaco,'v50_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from inicial
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($v50_inicial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " v50_inicial = $v50_inicial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$v50_inicial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$v50_inicial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$v50_inicial;
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
        $this->erro_sql   = "Record Vazio na Tabela:inicial";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $v50_inicial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from inicial ";
     $sql .= "      inner join db_config  on  db_config.codigo = inicial.v50_instit";
     $sql .= "      inner join advog  on  advog.v57_numcgm = inicial.v50_advog";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = inicial.v50_id_login";
     $sql .= "      inner join localiza  on  localiza.v54_codlocal = inicial.v50_codlocal";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join cgm  as a on   a.z01_numcgm = advog.v57_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($v50_inicial!=null ){
         $sql2 .= " where inicial.v50_inicial = $v50_inicial "; 
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
   function sql_query_file ( $v50_inicial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from inicial ";
     $sql2 = "";
     if($dbwhere==""){
       if($v50_inicial!=null ){
         $sql2 .= " where inicial.v50_inicial = $v50_inicial "; 
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
   function sql_query_inform( $v50_inicial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from inicial ";
     $sql .= "      left join inicialcert 		   on inicialcert.v51_inicial 	             = inicial.v50_inicial	                 ";
     $sql .= "      left join arreforo 			     on inicialcert.v51_certidao 	             = arreforo.k00_certidao                 ";
     $sql .= "      left join processoforoinicial     on processoforoinicial.v71_inicial   = inicial.v50_inicial	                 ";
     $sql .= "                                       and processoforoinicial.v71_anulado is false                                  ";
     $sql .= "      left join processoforo            on processoforo.v70_sequencial       = processoforoinicial.v71_processoforo  ";
     $sql .= "      left join vara  			            on vara.v53_codvara                  = processoforo.v70_vara 	               ";
     $sql .= "      left join inicialnomes  	        on inicialnomes.v58_inicial          = inicial.v50_inicial	                 ";
     $sql .= "      left join cgm as a 			          on a.z01_numcgm 			               = inicialnomes.v58_numcgm               ";
     $sql .= "      left join advog  			            on advog.v57_numcgm		               = inicial.v50_advog		                 ";
     $sql .= "      left join db_usuarios  		        on db_usuarios.id_usuario            = inicial.v50_id_login	                 ";
     $sql .= "      left join localiza  		          on localiza.v54_codlocal 	           = inicial.v50_codlocal	                 ";
     $sql .= "      left join cgm as b 			          on b.z01_numcgm 			               = advog.v57_numcgm		                   ";
     $sql .= "      left join inicialmov  		        on inicialmov.v56_codmov 	           = inicial.v50_codmov	                   ";
     $sql .= "      left join situacao  		          on situacao.v52_codsit 	             = inicialmov.v56_codsit	               ";

     $sql2 = "";
     if($dbwhere==""){
       if($v50_inicial!=null ){
         $sql2 .= " where inicial.v50_inicial = $v50_inicial ";
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
   function sql_query_pet( $v50_inicial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from inicial ";
     $sql .= "      inner join advog  on  advog.v57_numcgm = inicial.v50_advog";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = inicial.v50_id_login";
     $sql .= "      inner join localiza  on  localiza.v54_codlocal = inicial.v50_codlocal";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = advog.v57_numcgm";
     $sql .= "      inner join inicialmov  on  inicialmov.v56_codmov = inicial.v50_codmov";
     $sql .= "      inner join situacao  on situacao.v52_codsit = inicialmov.v56_codsit";

     $sql2 = "";
     if($dbwhere==""){
       if($v50_inicial!=null ){
         $sql2 .= " where inicial.v50_inicial = $v50_inicial ";
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
   function sql_query_sit( $v50_inicial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from inicial ";
     $sql .= "      inner join advog  on  advog.v57_numcgm = inicial.v50_advog";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = inicial.v50_id_login";
     $sql .= "      inner join localiza  on  localiza.v54_codlocal = inicial.v50_codlocal";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = advog.v57_numcgm";
     $sql .= "      inner join inicialmov  on  inicialmov.v56_codmov = inicial.v50_codmov";
     $sql .= "      inner join situacao  on situacao.v52_codsit = inicialmov.v56_codsit";
     $sql .= "      left join jurpeticoes on jurpeticoes.v60_inicial = inicial.v50_inicial";

     $sql2 = "";
     if($dbwhere==""){
       if($v50_inicial!=null ){
         $sql2 .= " where inicial.v50_inicial = $v50_inicial ";
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
   function sql_query_sitpar( $v50_inicial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from inicial ";
     $sql .= "      inner join advog  on  advog.v57_numcgm = inicial.v50_advog";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = inicial.v50_id_login";
     $sql .= "      inner join localiza  on  localiza.v54_codlocal = inicial.v50_codlocal";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = advog.v57_numcgm";
     $sql .= "      inner join inicialmov  on  inicialmov.v56_codmov = inicial.v50_codmov and v56_codsit=4";
     $sql .= "      inner join situacao  on situacao.v52_codsit = inicialmov.v56_codsit";
     $sql .= "      left join jurpeticoes on jurpeticoes.v60_inicial = inicial.v50_inicial";

     $sql2 = "";
     if($dbwhere==""){
       if($v50_inicial!=null ){
         $sql2 .= " where inicial.v50_inicial = $v50_inicial ";
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
	
	 function sql_query_sitmanual ( $v50_inicial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from inicial ";
		 
		 
     $sql .= "      inner join  inicialmov on v56_inicial = v50_inicial and v56_codmov = v50_codmov";
     $sql .= "      inner join situacao on v52_codsit = v56_codsit";
     $sql .= "      inner join db_config  on  db_config.codigo = inicial.v50_instit";
     $sql .= "      inner join advog  on  advog.v57_numcgm = inicial.v50_advog";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = inicial.v50_id_login";
     $sql .= "      inner join localiza  on  localiza.v54_codlocal = inicial.v50_codlocal";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join cgm  as a on   a.z01_numcgm = advog.v57_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($v50_inicial!=null ){
         $sql2 .= " where inicial.v50_inicial = $v50_inicial "; 
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
     * Função que calcula o valor total da inicial
     *
     */
    function valorInicialAtualizado($iInicial, $dDtEmiss=null) {
    	
        require_once("libs/db_sql.php");
        $nValor = 0;
        $sSqlCertidoesInicial = "select v51_inicial, 
    	                                  v13_certid, 
    	                                  v13_dtemis 
            	                     from inicialcert
            	                    inner join certid   on v13_certid = v51_certidao 
    	                            where v51_inicial = $iInicial ";
    	  $rsCertidoesInicial   = db_query($sSqlCertidoesInicial);
    	  for ($iCertidoesInicial = 0; $iCertidoesInicial < pg_num_rows($rsCertidoesInicial); $iCertidoesInicial++) {
    	    $oCertidoesInicial = db_utils::fieldsMemory($rsCertidoesInicial, $iCertidoesInicial);
    	    
          if ($dDtEmiss == null) {
            $dDtEmiss = $oCertidoesInicial->v13_dtemis;
          }
          
          $sSqlParcelamentos  = " select v14_parcel, ";
          $sSqlParcelamentos .= "        v07_numpre  ";
          $sSqlParcelamentos .= "   from certter";
          $sSqlParcelamentos .= "  inner join termo on v07_parcel = v14_parcel";
          $sSqlParcelamentos .= "  where v14_certid = {$oCertidoesInicial->v13_certid} ";
          $rsParcelamentos    = db_query($sSqlParcelamentos);
          $iLinhasParcel      = pg_num_rows($rsParcelamentos);
          
          for ( $iIndParcel=0; $iIndParcel < $iLinhasParcel; $iIndParcel++) {
             $oDadosParcel = db_utils::fieldsMemory($rsParcelamentos,$iIndParcel);
          
             $rsDadosDebitoCorrigido = debitos_numpre($oDadosParcel->v07_numpre, 0, 0, 
                                                      mktime(0, 0, 0, substr($dDtEmiss, 5, 2), 
                                                      substr($dDtEmiss, 8, 2), 
                                                      substr($dDtEmiss, 0, 4)), 
                                                      substr($dDtEmiss, 0, 4), 0);
          
             if ( $rsDadosDebitoCorrigido != false ) {
          
                $iLinhasDebito = pg_num_rows($rsDadosDebitoCorrigido);
          
                for ($iIndDebito = 0; $iIndDebito < $iLinhasDebito; $iIndDebito++) {
                   $nValor += db_utils::fieldsMemory($rsDadosDebitoCorrigido,$iIndDebito)->total;
                }
             }
          }

          $sSqlDadosDebitos  = " select distinct    ";
          $sSqlDadosDebitos .= "        k00_numpre, ";
          $sSqlDadosDebitos .= "        k00_numpar  ";
          $sSqlDadosDebitos .= "   from certdiv";
          $sSqlDadosDebitos .= "        inner join divida   on certdiv.v14_coddiv = divida.v01_coddiv";
          $sSqlDadosDebitos .= "        inner join arrecad  on arrecad.k00_numpre = divida.v01_numpre ";
          $sSqlDadosDebitos .= "                           and arrecad.k00_numpar = divida.v01_numpar";
          $sSqlDadosDebitos .= "  where v14_certid in ($oCertidoesInicial->v13_certid)";
          $rsDadosDebitos      = db_query($sSqlDadosDebitos);
          $iLinhasDadosDebitos = pg_num_rows($rsDadosDebitos); 
          
          for ( $iIndDadosDebitos = 0; $iIndDadosDebitos < $iLinhasDadosDebitos; $iIndDadosDebitos++ ) {
          
            $oDivida = db_utils::fieldsmemory($rsDadosDebitos, $iIndDadosDebitos);
            $rsDadosDebitoCorrigido = debitos_numpre($oDivida->k00_numpre, 0, 0, 
                                                     mktime(0, 0, 0, substr($dDtEmiss, 5, 2), 
                                                     substr($dDtEmiss, 8, 2), 
                                                     substr($dDtEmiss, 0, 4)), 
                                                     substr($dDtEmiss, 0, 4), 
                                                     $oDivida->k00_numpar);
          
            for ($iIndDebito = 0; $iIndDebito < pg_numrows($rsDadosDebitoCorrigido); $iIndDebito++) {
              $nValor += db_utils::fieldsMemory($rsDadosDebitoCorrigido, $iIndDebito)->total;
            }
            
          }
          
    	  }
    	  
    	  return $nValor;
    }  


	function sql_queryIniciaisPeticao($iInstituicao, $iCodigoSituacao) {
	  
	  $sCondicao = $iCodigoSituacao == 4 ? "<>" : "="; 
	  
	  $sWhere = "jurpeticoes.v60_inicial is null";
	  if ($iCodigoSituacao == 8) {
      $sWhere .= " or not exists (select 1 from jurpeticoes j where j.v60_inicial = inicial.v50_inicial and j.v60_tipopet = 2)";
	  }
	  
	  $sSql  = "select inicial,                                                                                                                                        \n";
    $sSql .= "       situacao_movimentacao,                                                                                                                          \n";
    $sSql .= "       localizacao,                                                                                                                                    \n";
    $sSql .= "       advogado,                                                                                                                                       \n";
    $sSql .= "       processoforo,                                                                                                                                   \n";
    $sSql .= "       parcelas_abertas,                                                                                                                               \n";
	  $sSql .= "       case when codigo_situacao = 4 and parcelas_abertas <> 0 then 'Inicial Parcelada' else 'Inicial Quitada' end as situacao                         \n";
	  $sSql .= "  from (select *,                                                                                                                                      \n";
	  $sSql .= "               (select count(distinct  arrecad.k00_numpar) from arrecad where arrecad.k00_numpre = base.ultimo_numpre_parcelamento) as parcelas_abertas\n";
	  $sSql .= "          from (select distinct                                                                                                                        \n";
	  $sSql .= "                       v50_inicial as inicial,                                                                                                         \n";
	  $sSql .= "                       v56_codsit  as codigo_situacao,                                                                                                 \n";
	  $sSql .= "                       v52_descr   as situacao_movimentacao,                                                                                           \n";
	  $sSql .= "                       v54_descr   as localizacao,                                                                                                     \n";
	  $sSql .= "                       z01_nome    as advogado,                                                                                                        \n";
	  $sSql .= "                       v70_codforo as processoforo,                                                                                                    \n";
	  $sSql .= "                       (select rinumpre from fc_parc_origem_completo(termo.v07_numpre) where riseq = 1) as ultimo_numpre_parcelamento                  \n";
	  $sSql .= "                  from inicial                                                                                                                         \n";
	  $sSql .= "                 inner join advog               on advog.v57_numcgm                = inicial.v50_advog                                                 \n";
	  $sSql .= "                 inner join db_usuarios         on db_usuarios.id_usuario          = inicial.v50_id_login                                              \n";
	  $sSql .= "                 inner join localiza            on localiza.v54_codlocal           = inicial.v50_codlocal                                              \n";
	  $sSql .= "                 inner join cgm                 on cgm.z01_numcgm                  = advog.v57_numcgm                                                  \n";
	  $sSql .= "                 inner join inicialmov          on inicialmov.v56_codmov           = inicial.v50_codmov                                                \n";
	  $sSql .= "                 inner join situacao            on situacao.v52_codsit             = inicialmov.v56_codsit                                             \n";
	  $sSql .= "                  left join jurpeticoes         on jurpeticoes.v60_inicial         = inicial.v50_inicial                                               \n";
	  $sSql .= "                 inner join processoforoinicial on processoforoinicial.v71_inicial = inicial.v50_inicial                                               \n";
	  $sSql .= "                 inner join processoforo        on processoforo.v70_sequencial     = processoforoinicial.v71_processoforo                              \n";
	  $sSql .= "                  left join termoini            on termoini.inicial                = inicial.v50_inicial                                               \n";
	  $sSql .= "                  left join termo               on termo.v07_parcel                = termoini.parcel                                                   \n";
	  $sSql .= "                 where inicialmov.v56_codsit in (4, 8)                                                                                                 \n";
	  $sSql .= "                   and inicial.v50_instit    = {$iInstituicao}                                                                                         \n";
	  $sSql .= "                   and ({$sWhere})                                                                                                                     \n";
    $sSql .= "                   and ( case                                                                                                                          \n";
    $sSql .= "                            when termo.v07_parcel is not null                                                                                          \n";
    $sSql .= "                              then termo.v07_situacao = 1                                                                                              \n";
    $sSql .= "                            else true                                                                                                                  \n";
    $sSql .= "                            end                                                                                                                        \n";
    $sSql .= "                       )                                                                                                                               \n";
	  $sSql .= "                 order by v50_inicial) as base ) as base_filtro                                                                                        \n";
	  $sSql .= " where base_filtro.parcelas_abertas {$sCondicao} 0                                                                                                     \n";

    return $sSql;	
	}

	/**
	 * Busca inicias com filtros
	 * 
	 * @param integer $iInstituicao 
	 * @param string $sCampos 
	 * @param string $sOrdem 
	 * @param strinbg $sWhere 
	 * @access public
	 * @return string
	 */
	function sql_queryFiltroInicias($iInstituicao, $sCampos = "*", $sOrdem = null, $sWhere = null) {

		/**
		 * Ordena por padrao pelo codigo da inicial
		 */	 
		if ( $sOrdem == null ) {
			$sOrdem = "v50_inicial";
		}

		/**
		 * Adciona validacoes 
		 */	 
		if ( $sWhere != '' ) {
			$sWhere = 'and '.$sWhere;
		}

		$sSql  = "select distinct $sCampos 	 	  																														                      ";
		$sSql .= "  from inicial                                                                                                  ";
		$sSql .= "       inner join advog               on advog.v57_numcgm                = inicial.v50_advog                    ";
		$sSql .= "       inner join db_usuarios         on db_usuarios.id_usuario          = inicial.v50_id_login                 ";
		$sSql .= "       inner join localiza            on localiza.v54_codlocal           = inicial.v50_codlocal                 ";
		$sSql .= "       inner join cgm                 on cgm.z01_numcgm                  = advog.v57_numcgm                     ";
		$sSql .= "       inner join inicialmov          on inicialmov.v56_codmov           = inicial.v50_codmov                   ";
		$sSql .= "       inner join inicialnomes        on inicialnomes.v58_inicial        = inicial.v50_inicial                  ";
		$sSql .= "       inner join situacao            on situacao.v52_codsit             = inicialmov.v56_codsit                ";
		$sSql .= "       left  join processoforoinicial on processoforoinicial.v71_inicial = inicial.v50_inicial                  ";
		$sSql .= "       left  join processoforo        on processoforo.v70_sequencial     = processoforoinicial.v71_processoforo ";
		$sSql .= "       left  join vara                on processoforo.v70_vara           = vara.v53_codvara                     ";
		$sSql .= " where inicial.v50_instit = {$iInstituicao}                                                                     ";
		$sSql .= $sWhere;
		$sSql .= " order by $sOrdem                                                                                               ";  
			
		return $sSql;	
	}
    
}
?>