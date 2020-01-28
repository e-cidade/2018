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

//MODULO: material
//CLASSE DA ENTIDADE matestoquedev
class cl_matestoquedev { 
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
   var $m45_codigo = 0; 
   var $m45_codmatrequi = 0; 
   var $m45_codatendrequi = 0; 
   var $m45_data_dia = null; 
   var $m45_data_mes = null; 
   var $m45_data_ano = null; 
   var $m45_data = null; 
   var $m45_hora = null; 
   var $m45_login = 0; 
   var $m45_depto = 0; 
   var $m45_obs = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 m45_codigo = int8 = Código da Devolução 
                 m45_codmatrequi = int8 = Código da Requisição 
                 m45_codatendrequi = int8 = Código do Atendimento 
                 m45_data = date = Data da Devolução 
                 m45_hora = varchar(5) = Hora da Devolução 
                 m45_login = int4 = Cod. Usuário 
                 m45_depto = int4 = Depart. 
                 m45_obs = text = Observação 
                 ";
   //funcao construtor da classe 
   function cl_matestoquedev() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("matestoquedev"); 
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
       $this->m45_codigo = ($this->m45_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["m45_codigo"]:$this->m45_codigo);
       $this->m45_codmatrequi = ($this->m45_codmatrequi == ""?@$GLOBALS["HTTP_POST_VARS"]["m45_codmatrequi"]:$this->m45_codmatrequi);
       $this->m45_codatendrequi = ($this->m45_codatendrequi == ""?@$GLOBALS["HTTP_POST_VARS"]["m45_codatendrequi"]:$this->m45_codatendrequi);
       if($this->m45_data == ""){
         $this->m45_data_dia = ($this->m45_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["m45_data_dia"]:$this->m45_data_dia);
         $this->m45_data_mes = ($this->m45_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["m45_data_mes"]:$this->m45_data_mes);
         $this->m45_data_ano = ($this->m45_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["m45_data_ano"]:$this->m45_data_ano);
         if($this->m45_data_dia != ""){
            $this->m45_data = $this->m45_data_ano."-".$this->m45_data_mes."-".$this->m45_data_dia;
         }
       }
       $this->m45_hora = ($this->m45_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["m45_hora"]:$this->m45_hora);
       $this->m45_login = ($this->m45_login == ""?@$GLOBALS["HTTP_POST_VARS"]["m45_login"]:$this->m45_login);
       $this->m45_depto = ($this->m45_depto == ""?@$GLOBALS["HTTP_POST_VARS"]["m45_depto"]:$this->m45_depto);
       $this->m45_obs = ($this->m45_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["m45_obs"]:$this->m45_obs);
     }else{
       $this->m45_codigo = ($this->m45_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["m45_codigo"]:$this->m45_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($m45_codigo){ 
      $this->atualizacampos();
     if($this->m45_codmatrequi == null ){ 
       $this->erro_sql = " Campo Código da Requisição nao Informado.";
       $this->erro_campo = "m45_codmatrequi";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m45_codatendrequi == null ){ 
       $this->erro_sql = " Campo Código do Atendimento nao Informado.";
       $this->erro_campo = "m45_codatendrequi";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m45_data == null ){ 
       $this->erro_sql = " Campo Data da Devolução nao Informado.";
       $this->erro_campo = "m45_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m45_hora == null ){ 
       $this->erro_sql = " Campo Hora da Devolução nao Informado.";
       $this->erro_campo = "m45_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m45_login == null ){ 
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "m45_login";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m45_depto == null ){ 
       $this->erro_sql = " Campo Depart. nao Informado.";
       $this->erro_campo = "m45_depto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($m45_codigo == "" || $m45_codigo == null ){
       $result = db_query("select nextval('matestoquedev_m45_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: matestoquedev_m45_codigo_seq do campo: m45_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->m45_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from matestoquedev_m45_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $m45_codigo)){
         $this->erro_sql = " Campo m45_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->m45_codigo = $m45_codigo; 
       }
     }
     if(($this->m45_codigo == null) || ($this->m45_codigo == "") ){ 
       $this->erro_sql = " Campo m45_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into matestoquedev(
                                       m45_codigo 
                                      ,m45_codmatrequi 
                                      ,m45_codatendrequi 
                                      ,m45_data 
                                      ,m45_hora 
                                      ,m45_login 
                                      ,m45_depto 
                                      ,m45_obs 
                       )
                values (
                                $this->m45_codigo 
                               ,$this->m45_codmatrequi 
                               ,$this->m45_codatendrequi 
                               ,".($this->m45_data == "null" || $this->m45_data == ""?"null":"'".$this->m45_data."'")." 
                               ,'$this->m45_hora' 
                               ,$this->m45_login 
                               ,$this->m45_depto 
                               ,'$this->m45_obs' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "matestoquedev ($this->m45_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "matestoquedev já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "matestoquedev ($this->m45_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m45_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->m45_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6905,'$this->m45_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1136,6905,'','".AddSlashes(pg_result($resaco,0,'m45_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1136,6909,'','".AddSlashes(pg_result($resaco,0,'m45_codmatrequi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1136,6947,'','".AddSlashes(pg_result($resaco,0,'m45_codatendrequi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1136,6906,'','".AddSlashes(pg_result($resaco,0,'m45_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1136,6907,'','".AddSlashes(pg_result($resaco,0,'m45_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1136,6910,'','".AddSlashes(pg_result($resaco,0,'m45_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1136,6911,'','".AddSlashes(pg_result($resaco,0,'m45_depto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1136,6908,'','".AddSlashes(pg_result($resaco,0,'m45_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($m45_codigo=null) { 
      $this->atualizacampos();
     $sql = " update matestoquedev set ";
     $virgula = "";
     if(trim($this->m45_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m45_codigo"])){ 
       $sql  .= $virgula." m45_codigo = $this->m45_codigo ";
       $virgula = ",";
       if(trim($this->m45_codigo) == null ){ 
         $this->erro_sql = " Campo Código da Devolução nao Informado.";
         $this->erro_campo = "m45_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m45_codmatrequi)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m45_codmatrequi"])){ 
       $sql  .= $virgula." m45_codmatrequi = $this->m45_codmatrequi ";
       $virgula = ",";
       if(trim($this->m45_codmatrequi) == null ){ 
         $this->erro_sql = " Campo Código da Requisição nao Informado.";
         $this->erro_campo = "m45_codmatrequi";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m45_codatendrequi)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m45_codatendrequi"])){ 
       $sql  .= $virgula." m45_codatendrequi = $this->m45_codatendrequi ";
       $virgula = ",";
       if(trim($this->m45_codatendrequi) == null ){ 
         $this->erro_sql = " Campo Código do Atendimento nao Informado.";
         $this->erro_campo = "m45_codatendrequi";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m45_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m45_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["m45_data_dia"] !="") ){ 
       $sql  .= $virgula." m45_data = '$this->m45_data' ";
       $virgula = ",";
       if(trim($this->m45_data) == null ){ 
         $this->erro_sql = " Campo Data da Devolução nao Informado.";
         $this->erro_campo = "m45_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["m45_data_dia"])){ 
         $sql  .= $virgula." m45_data = null ";
         $virgula = ",";
         if(trim($this->m45_data) == null ){ 
           $this->erro_sql = " Campo Data da Devolução nao Informado.";
           $this->erro_campo = "m45_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->m45_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m45_hora"])){ 
       $sql  .= $virgula." m45_hora = '$this->m45_hora' ";
       $virgula = ",";
       if(trim($this->m45_hora) == null ){ 
         $this->erro_sql = " Campo Hora da Devolução nao Informado.";
         $this->erro_campo = "m45_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m45_login)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m45_login"])){ 
       $sql  .= $virgula." m45_login = $this->m45_login ";
       $virgula = ",";
       if(trim($this->m45_login) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "m45_login";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m45_depto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m45_depto"])){ 
       $sql  .= $virgula." m45_depto = $this->m45_depto ";
       $virgula = ",";
       if(trim($this->m45_depto) == null ){ 
         $this->erro_sql = " Campo Depart. nao Informado.";
         $this->erro_campo = "m45_depto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m45_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m45_obs"])){ 
       $sql  .= $virgula." m45_obs = '$this->m45_obs' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($m45_codigo!=null){
       $sql .= " m45_codigo = $this->m45_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->m45_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6905,'$this->m45_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m45_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1136,6905,'".AddSlashes(pg_result($resaco,$conresaco,'m45_codigo'))."','$this->m45_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m45_codmatrequi"]))
           $resac = db_query("insert into db_acount values($acount,1136,6909,'".AddSlashes(pg_result($resaco,$conresaco,'m45_codmatrequi'))."','$this->m45_codmatrequi',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m45_codatendrequi"]))
           $resac = db_query("insert into db_acount values($acount,1136,6947,'".AddSlashes(pg_result($resaco,$conresaco,'m45_codatendrequi'))."','$this->m45_codatendrequi',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m45_data"]))
           $resac = db_query("insert into db_acount values($acount,1136,6906,'".AddSlashes(pg_result($resaco,$conresaco,'m45_data'))."','$this->m45_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m45_hora"]))
           $resac = db_query("insert into db_acount values($acount,1136,6907,'".AddSlashes(pg_result($resaco,$conresaco,'m45_hora'))."','$this->m45_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m45_login"]))
           $resac = db_query("insert into db_acount values($acount,1136,6910,'".AddSlashes(pg_result($resaco,$conresaco,'m45_login'))."','$this->m45_login',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m45_depto"]))
           $resac = db_query("insert into db_acount values($acount,1136,6911,'".AddSlashes(pg_result($resaco,$conresaco,'m45_depto'))."','$this->m45_depto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m45_obs"]))
           $resac = db_query("insert into db_acount values($acount,1136,6908,'".AddSlashes(pg_result($resaco,$conresaco,'m45_obs'))."','$this->m45_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "matestoquedev nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->m45_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "matestoquedev nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->m45_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m45_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($m45_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($m45_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6905,'$m45_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1136,6905,'','".AddSlashes(pg_result($resaco,$iresaco,'m45_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1136,6909,'','".AddSlashes(pg_result($resaco,$iresaco,'m45_codmatrequi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1136,6947,'','".AddSlashes(pg_result($resaco,$iresaco,'m45_codatendrequi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1136,6906,'','".AddSlashes(pg_result($resaco,$iresaco,'m45_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1136,6907,'','".AddSlashes(pg_result($resaco,$iresaco,'m45_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1136,6910,'','".AddSlashes(pg_result($resaco,$iresaco,'m45_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1136,6911,'','".AddSlashes(pg_result($resaco,$iresaco,'m45_depto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1136,6908,'','".AddSlashes(pg_result($resaco,$iresaco,'m45_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from matestoquedev
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($m45_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " m45_codigo = $m45_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "matestoquedev nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$m45_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "matestoquedev nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$m45_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$m45_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:matestoquedev";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $m45_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matestoquedev ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = matestoquedev.m45_login";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = matestoquedev.m45_depto";
     $sql .= "      inner join matrequi  on  matrequi.m40_codigo = matestoquedev.m45_codmatrequi";
     $sql .= "      inner join atendrequi  on  atendrequi.m42_codigo = matestoquedev.m45_codatendrequi";
     $sql .= "      inner join db_usuarios as d on  d.id_usuario = matrequi.m40_login";
     $sql .= "      inner join db_depart  as a on   a.coddepto = matrequi.m40_depto";
     $sql .= "      inner join db_usuarios as c  on  c.id_usuario = atendrequi.m42_login";
     $sql .= "      inner join db_depart  as b on   b.coddepto = atendrequi.m42_depto";
     $sql2 = "";
     if($dbwhere==""){
       if($m45_codigo!=null ){
         $sql2 .= " where matestoquedev.m45_codigo = $m45_codigo "; 
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
  
  function sql_query_itens_devolvidos ( $m45_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from matestoquedev ";
		$sql .= "      inner join matestoquedevitem on matestoquedevitem.m46_codmatestoquedev = matestoquedev.m45_codigo"; 
		$sql .= "      inner join matrequiitem on matestoquedevitem.m46_codmatrequiitem = matrequiitem.m41_codigo";
		$sql .= "      inner join db_depart on matestoquedev.m45_depto = db_depart.coddepto ";
		$sql .= "      inner join db_usuarios on matestoquedev.m45_login = db_usuarios.id_usuario ";
		$sql .= "      inner join atendrequi on matestoquedev.m45_codatendrequi = atendrequi.m42_codigo";
    $sql2 = "";
    if($dbwhere==""){
      if($m45_codigo!=null ){
        $sql2 .= " where matestoquedev.m45_codigo = $m45_codigo ";
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
  
   function sql_query_file ( $m45_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matestoquedev ";
     $sql2 = "";
     if($dbwhere==""){
       if($m45_codigo!=null ){
         $sql2 .= " where matestoquedev.m45_codigo = $m45_codigo "; 
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