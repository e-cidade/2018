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

//MODULO: protocolo
//CLASSE DA ENTIDADE cgmcorreto
class cl_cgmcorreto { 
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
   var $z10_codigo = 0; 
   var $z10_numcgm = 0; 
   var $z10_data_dia = null; 
   var $z10_data_mes = null; 
   var $z10_data_ano = null; 
   var $z10_data = null; 
   var $z10_hora = null; 
   var $z10_login = 0; 
   var $z10_proc = 'f'; 
   var $z10_instit = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 z10_codigo = int4 = Código 
                 z10_numcgm = int4 = Numcgm 
                 z10_data = date = Data 
                 z10_hora = char(5) = Hora 
                 z10_login = int4 = Cod. Usuário 
                 z10_proc = bool = Se registro ja foi processado 
                 z10_instit = int4 = Cod. Instituição 
                 ";
   //funcao construtor da classe 
   function cl_cgmcorreto() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cgmcorreto"); 
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
       $this->z10_codigo = ($this->z10_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["z10_codigo"]:$this->z10_codigo);
       $this->z10_numcgm = ($this->z10_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["z10_numcgm"]:$this->z10_numcgm);
       if($this->z10_data == ""){
         $this->z10_data_dia = ($this->z10_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["z10_data_dia"]:$this->z10_data_dia);
         $this->z10_data_mes = ($this->z10_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["z10_data_mes"]:$this->z10_data_mes);
         $this->z10_data_ano = ($this->z10_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["z10_data_ano"]:$this->z10_data_ano);
         if($this->z10_data_dia != ""){
            $this->z10_data = $this->z10_data_ano."-".$this->z10_data_mes."-".$this->z10_data_dia;
         }
       }
       $this->z10_hora = ($this->z10_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["z10_hora"]:$this->z10_hora);
       $this->z10_login = ($this->z10_login == ""?@$GLOBALS["HTTP_POST_VARS"]["z10_login"]:$this->z10_login);
       $this->z10_proc = ($this->z10_proc == "f"?@$GLOBALS["HTTP_POST_VARS"]["z10_proc"]:$this->z10_proc);
       $this->z10_instit = ($this->z10_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["z10_instit"]:$this->z10_instit);
     }else{
       $this->z10_codigo = ($this->z10_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["z10_codigo"]:$this->z10_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($z10_codigo){ 
      $this->atualizacampos();
     if($this->z10_numcgm == null ){ 
       $this->erro_sql = " Campo Numcgm nao Informado.";
       $this->erro_campo = "z10_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z10_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "z10_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z10_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "z10_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z10_login == null ){ 
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "z10_login";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z10_proc == null ){ 
       $this->erro_sql = " Campo Se registro ja foi processado nao Informado.";
       $this->erro_campo = "z10_proc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z10_instit == null ){ 
       $this->erro_sql = " Campo Cod. Instituição nao Informado.";
       $this->erro_campo = "z10_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($z10_codigo == "" || $z10_codigo == null ){
       $result = db_query("select nextval('cgmcorreto_z10_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cgmcorreto_z10_codigo_seq do campo: z10_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->z10_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from cgmcorreto_z10_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $z10_codigo)){
         $this->erro_sql = " Campo z10_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->z10_codigo = $z10_codigo; 
       }
     }
     if(($this->z10_codigo == null) || ($this->z10_codigo == "") ){ 
       $this->erro_sql = " Campo z10_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cgmcorreto(
                                       z10_codigo 
                                      ,z10_numcgm 
                                      ,z10_data 
                                      ,z10_hora 
                                      ,z10_login 
                                      ,z10_proc 
                                      ,z10_instit 
                       )
                values (
                                $this->z10_codigo 
                               ,$this->z10_numcgm 
                               ,".($this->z10_data == "null" || $this->z10_data == ""?"null":"'".$this->z10_data."'")." 
                               ,'$this->z10_hora' 
                               ,$this->z10_login 
                               ,'$this->z10_proc' 
                               ,$this->z10_instit 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "CGM's corretos ($this->z10_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "CGM's corretos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "CGM's corretos ($this->z10_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->z10_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->z10_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5152,'$this->z10_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,736,5152,'','".AddSlashes(pg_result($resaco,0,'z10_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,736,5153,'','".AddSlashes(pg_result($resaco,0,'z10_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,736,5154,'','".AddSlashes(pg_result($resaco,0,'z10_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,736,5155,'','".AddSlashes(pg_result($resaco,0,'z10_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,736,5156,'','".AddSlashes(pg_result($resaco,0,'z10_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,736,5157,'','".AddSlashes(pg_result($resaco,0,'z10_proc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,736,10682,'','".AddSlashes(pg_result($resaco,0,'z10_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($z10_codigo=null) { 
      $this->atualizacampos();
     $sql = " update cgmcorreto set ";
     $virgula = "";
     if(trim($this->z10_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z10_codigo"])){ 
       $sql  .= $virgula." z10_codigo = $this->z10_codigo ";
       $virgula = ",";
       if(trim($this->z10_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "z10_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z10_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z10_numcgm"])){ 
       $sql  .= $virgula." z10_numcgm = $this->z10_numcgm ";
       $virgula = ",";
       if(trim($this->z10_numcgm) == null ){ 
         $this->erro_sql = " Campo Numcgm nao Informado.";
         $this->erro_campo = "z10_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z10_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z10_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["z10_data_dia"] !="") ){ 
       $sql  .= $virgula." z10_data = '$this->z10_data' ";
       $virgula = ",";
       if(trim($this->z10_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "z10_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["z10_data_dia"])){ 
         $sql  .= $virgula." z10_data = null ";
         $virgula = ",";
         if(trim($this->z10_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "z10_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->z10_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z10_hora"])){ 
       $sql  .= $virgula." z10_hora = '$this->z10_hora' ";
       $virgula = ",";
       if(trim($this->z10_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "z10_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z10_login)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z10_login"])){ 
       $sql  .= $virgula." z10_login = $this->z10_login ";
       $virgula = ",";
       if(trim($this->z10_login) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "z10_login";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z10_proc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z10_proc"])){ 
       $sql  .= $virgula." z10_proc = '$this->z10_proc' ";
       $virgula = ",";
       if(trim($this->z10_proc) == null ){ 
         $this->erro_sql = " Campo Se registro ja foi processado nao Informado.";
         $this->erro_campo = "z10_proc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z10_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z10_instit"])){ 
       $sql  .= $virgula." z10_instit = $this->z10_instit ";
       $virgula = ",";
       if(trim($this->z10_instit) == null ){ 
         $this->erro_sql = " Campo Cod. Instituição nao Informado.";
         $this->erro_campo = "z10_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($z10_codigo!=null){
       $sql .= " z10_codigo = $this->z10_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->z10_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5152,'$this->z10_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z10_codigo"]))
           $resac = db_query("insert into db_acount values($acount,736,5152,'".AddSlashes(pg_result($resaco,$conresaco,'z10_codigo'))."','$this->z10_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z10_numcgm"]))
           $resac = db_query("insert into db_acount values($acount,736,5153,'".AddSlashes(pg_result($resaco,$conresaco,'z10_numcgm'))."','$this->z10_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z10_data"]))
           $resac = db_query("insert into db_acount values($acount,736,5154,'".AddSlashes(pg_result($resaco,$conresaco,'z10_data'))."','$this->z10_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z10_hora"]))
           $resac = db_query("insert into db_acount values($acount,736,5155,'".AddSlashes(pg_result($resaco,$conresaco,'z10_hora'))."','$this->z10_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z10_login"]))
           $resac = db_query("insert into db_acount values($acount,736,5156,'".AddSlashes(pg_result($resaco,$conresaco,'z10_login'))."','$this->z10_login',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z10_proc"]))
           $resac = db_query("insert into db_acount values($acount,736,5157,'".AddSlashes(pg_result($resaco,$conresaco,'z10_proc'))."','$this->z10_proc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z10_instit"]))
           $resac = db_query("insert into db_acount values($acount,736,10682,'".AddSlashes(pg_result($resaco,$conresaco,'z10_instit'))."','$this->z10_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "CGM's corretos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->z10_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "CGM's corretos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->z10_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->z10_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($z10_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($z10_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5152,'$z10_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,736,5152,'','".AddSlashes(pg_result($resaco,$iresaco,'z10_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,736,5153,'','".AddSlashes(pg_result($resaco,$iresaco,'z10_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,736,5154,'','".AddSlashes(pg_result($resaco,$iresaco,'z10_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,736,5155,'','".AddSlashes(pg_result($resaco,$iresaco,'z10_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,736,5156,'','".AddSlashes(pg_result($resaco,$iresaco,'z10_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,736,5157,'','".AddSlashes(pg_result($resaco,$iresaco,'z10_proc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,736,10682,'','".AddSlashes(pg_result($resaco,$iresaco,'z10_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from cgmcorreto
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($z10_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " z10_codigo = $z10_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "CGM's corretos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$z10_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "CGM's corretos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$z10_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$z10_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:cgmcorreto";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $z10_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= '*';//$campos;
     }
     $sql .= " from cgmcorreto ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = cgmcorreto.z10_numcgm";
     $sql .= "      inner join db_config  on  db_config.codigo = cgmcorreto.z10_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = cgmcorreto.z10_login";
     $sql2 = "";
     if($dbwhere==""){
       if($z10_codigo!=null ){
         $sql2 .= " where cgmcorreto.z10_codigo = $z10_codigo "; 
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
   function sql_query_file ( $z10_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cgmcorreto ";
     $sql2 = "";
     if($dbwhere==""){
       if($z10_codigo!=null ){
         $sql2 .= " where cgmcorreto.z10_codigo = $z10_codigo "; 
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
   function sql_query_cgmduploprocessado ( $z10_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= '*';//$campos;
     }
    $sql .= " from cgmcorreto ";
     $sql .= "      inner join cgmerrado   on z10_codigo = z11_codigo ";
     $sql .= "      inner join db_usuarios on id_usuario = z10_login ";
     $sql .= "      inner join cgm         on z01_numcgm = z10_numcgm ";
     $sql2 = "";
     if($dbwhere==""){
       if($z10_codigo!=null ){
         $sql2 .= " where cgmcorreto.z10_codigo = $z10_codigo "; 
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