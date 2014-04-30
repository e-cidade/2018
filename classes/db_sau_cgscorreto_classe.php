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

//MODULO: Ambulatorial
//CLASSE DA ENTIDADE sau_cgscorreto
class cl_sau_cgscorreto { 
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
   var $s127_i_codigo = 0; 
   var $s127_i_numcgs = 0; 
   var $s127_d_data_dia = null; 
   var $s127_d_data_mes = null; 
   var $s127_d_data_ano = null; 
   var $s127_d_data = null; 
   var $s127_c_hora = null; 
   var $s127_i_login = 0; 
   var $s127_b_proc = 'f'; 
   var $s127_i_instit = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 s127_i_codigo = int4 = Código 
                 s127_i_numcgs = int4 = CGS 
                 s127_d_data = date = Data 
                 s127_c_hora = char(5) = Hora 
                 s127_i_login = int4 = Login 
                 s127_b_proc = bool = Se registro ja foi processado 
                 s127_i_instit = int4 = Instituição 
                 ";
   //funcao construtor da classe 
   function cl_sau_cgscorreto() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("sau_cgscorreto"); 
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
       $this->s127_i_codigo = ($this->s127_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["s127_i_codigo"]:$this->s127_i_codigo);
       $this->s127_i_numcgs = ($this->s127_i_numcgs == ""?@$GLOBALS["HTTP_POST_VARS"]["s127_i_numcgs"]:$this->s127_i_numcgs);
       if($this->s127_d_data == ""){
         $this->s127_d_data_dia = ($this->s127_d_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["s127_d_data_dia"]:$this->s127_d_data_dia);
         $this->s127_d_data_mes = ($this->s127_d_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["s127_d_data_mes"]:$this->s127_d_data_mes);
         $this->s127_d_data_ano = ($this->s127_d_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["s127_d_data_ano"]:$this->s127_d_data_ano);
         if($this->s127_d_data_dia != ""){
            $this->s127_d_data = $this->s127_d_data_ano."-".$this->s127_d_data_mes."-".$this->s127_d_data_dia;
         }
       }
       $this->s127_c_hora = ($this->s127_c_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["s127_c_hora"]:$this->s127_c_hora);
       $this->s127_i_login = ($this->s127_i_login == ""?@$GLOBALS["HTTP_POST_VARS"]["s127_i_login"]:$this->s127_i_login);
       $this->s127_b_proc = ($this->s127_b_proc == "f"?@$GLOBALS["HTTP_POST_VARS"]["s127_b_proc"]:$this->s127_b_proc);
       $this->s127_i_instit = ($this->s127_i_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["s127_i_instit"]:$this->s127_i_instit);
     }else{
       $this->s127_i_codigo = ($this->s127_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["s127_i_codigo"]:$this->s127_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($s127_i_codigo){ 
      $this->atualizacampos();
     if($this->s127_i_numcgs == null ){ 
       $this->erro_sql = " Campo CGS nao Informado.";
       $this->erro_campo = "s127_i_numcgs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s127_d_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "s127_d_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s127_c_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "s127_c_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s127_i_login == null ){ 
       $this->erro_sql = " Campo Login nao Informado.";
       $this->erro_campo = "s127_i_login";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s127_b_proc == null ){ 
       $this->erro_sql = " Campo Se registro ja foi processado nao Informado.";
       $this->erro_campo = "s127_b_proc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s127_i_instit == null ){ 
       $this->erro_sql = " Campo Instituição nao Informado.";
       $this->erro_campo = "s127_i_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($s127_i_codigo == "" || $s127_i_codigo == null ){
       $result = db_query("select nextval('sau_cgscorreto_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: sau_cgscorreto_codigo_seq do campo: s127_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->s127_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from sau_cgscorreto_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $s127_i_codigo)){
         $this->erro_sql = " Campo s127_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->s127_i_codigo = $s127_i_codigo; 
       }
     }
     if(($this->s127_i_codigo == null) || ($this->s127_i_codigo == "") ){ 
       $this->erro_sql = " Campo s127_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into sau_cgscorreto(
                                       s127_i_codigo 
                                      ,s127_i_numcgs 
                                      ,s127_d_data 
                                      ,s127_c_hora 
                                      ,s127_i_login 
                                      ,s127_b_proc 
                                      ,s127_i_instit 
                       )
                values (
                                $this->s127_i_codigo 
                               ,$this->s127_i_numcgs 
                               ,".($this->s127_d_data == "null" || $this->s127_d_data == ""?"null":"'".$this->s127_d_data."'")." 
                               ,'$this->s127_c_hora' 
                               ,$this->s127_i_login 
                               ,'$this->s127_b_proc' 
                               ,$this->s127_i_instit 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "cgscorreto ($this->s127_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "cgscorreto já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "cgscorreto ($this->s127_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->s127_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->s127_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15466,'$this->s127_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2712,15466,'','".AddSlashes(pg_result($resaco,0,'s127_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2712,15467,'','".AddSlashes(pg_result($resaco,0,'s127_i_numcgs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2712,15468,'','".AddSlashes(pg_result($resaco,0,'s127_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2712,15469,'','".AddSlashes(pg_result($resaco,0,'s127_c_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2712,15470,'','".AddSlashes(pg_result($resaco,0,'s127_i_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2712,15471,'','".AddSlashes(pg_result($resaco,0,'s127_b_proc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2712,15472,'','".AddSlashes(pg_result($resaco,0,'s127_i_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($s127_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update sau_cgscorreto set ";
     $virgula = "";
     if(trim($this->s127_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s127_i_codigo"])){ 
       $sql  .= $virgula." s127_i_codigo = $this->s127_i_codigo ";
       $virgula = ",";
       if(trim($this->s127_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "s127_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s127_i_numcgs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s127_i_numcgs"])){ 
       $sql  .= $virgula." s127_i_numcgs = $this->s127_i_numcgs ";
       $virgula = ",";
       if(trim($this->s127_i_numcgs) == null ){ 
         $this->erro_sql = " Campo CGS nao Informado.";
         $this->erro_campo = "s127_i_numcgs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s127_d_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s127_d_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["s127_d_data_dia"] !="") ){ 
       $sql  .= $virgula." s127_d_data = '$this->s127_d_data' ";
       $virgula = ",";
       if(trim($this->s127_d_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "s127_d_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["s127_d_data_dia"])){ 
         $sql  .= $virgula." s127_d_data = null ";
         $virgula = ",";
         if(trim($this->s127_d_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "s127_d_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->s127_c_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s127_c_hora"])){ 
       $sql  .= $virgula." s127_c_hora = '$this->s127_c_hora' ";
       $virgula = ",";
       if(trim($this->s127_c_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "s127_c_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s127_i_login)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s127_i_login"])){ 
       $sql  .= $virgula." s127_i_login = $this->s127_i_login ";
       $virgula = ",";
       if(trim($this->s127_i_login) == null ){ 
         $this->erro_sql = " Campo Login nao Informado.";
         $this->erro_campo = "s127_i_login";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s127_b_proc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s127_b_proc"])){ 
       $sql  .= $virgula." s127_b_proc = '$this->s127_b_proc' ";
       $virgula = ",";
       if(trim($this->s127_b_proc) == null ){ 
         $this->erro_sql = " Campo Se registro ja foi processado nao Informado.";
         $this->erro_campo = "s127_b_proc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s127_i_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s127_i_instit"])){ 
       $sql  .= $virgula." s127_i_instit = $this->s127_i_instit ";
       $virgula = ",";
       if(trim($this->s127_i_instit) == null ){ 
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "s127_i_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($s127_i_codigo!=null){
       $sql .= " s127_i_codigo = $this->s127_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->s127_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15466,'$this->s127_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s127_i_codigo"]) || $this->s127_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2712,15466,'".AddSlashes(pg_result($resaco,$conresaco,'s127_i_codigo'))."','$this->s127_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s127_i_numcgs"]) || $this->s127_i_numcgs != "")
           $resac = db_query("insert into db_acount values($acount,2712,15467,'".AddSlashes(pg_result($resaco,$conresaco,'s127_i_numcgs'))."','$this->s127_i_numcgs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s127_d_data"]) || $this->s127_d_data != "")
           $resac = db_query("insert into db_acount values($acount,2712,15468,'".AddSlashes(pg_result($resaco,$conresaco,'s127_d_data'))."','$this->s127_d_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s127_c_hora"]) || $this->s127_c_hora != "")
           $resac = db_query("insert into db_acount values($acount,2712,15469,'".AddSlashes(pg_result($resaco,$conresaco,'s127_c_hora'))."','$this->s127_c_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s127_i_login"]) || $this->s127_i_login != "")
           $resac = db_query("insert into db_acount values($acount,2712,15470,'".AddSlashes(pg_result($resaco,$conresaco,'s127_i_login'))."','$this->s127_i_login',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s127_b_proc"]) || $this->s127_b_proc != "")
           $resac = db_query("insert into db_acount values($acount,2712,15471,'".AddSlashes(pg_result($resaco,$conresaco,'s127_b_proc'))."','$this->s127_b_proc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s127_i_instit"]) || $this->s127_i_instit != "")
           $resac = db_query("insert into db_acount values($acount,2712,15472,'".AddSlashes(pg_result($resaco,$conresaco,'s127_i_instit'))."','$this->s127_i_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "cgscorreto nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->s127_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "cgscorreto nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->s127_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->s127_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($s127_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($s127_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15466,'$s127_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2712,15466,'','".AddSlashes(pg_result($resaco,$iresaco,'s127_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2712,15467,'','".AddSlashes(pg_result($resaco,$iresaco,'s127_i_numcgs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2712,15468,'','".AddSlashes(pg_result($resaco,$iresaco,'s127_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2712,15469,'','".AddSlashes(pg_result($resaco,$iresaco,'s127_c_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2712,15470,'','".AddSlashes(pg_result($resaco,$iresaco,'s127_i_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2712,15471,'','".AddSlashes(pg_result($resaco,$iresaco,'s127_b_proc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2712,15472,'','".AddSlashes(pg_result($resaco,$iresaco,'s127_i_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from sau_cgscorreto
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($s127_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " s127_i_codigo = $s127_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }

     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "cgscorreto nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$s127_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "cgscorreto nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$s127_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$s127_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:sau_cgscorreto";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $s127_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from sau_cgscorreto ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = sau_cgscorreto.s127_i_login";
     $sql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = sau_cgscorreto.s127_i_instit";
     $sql .= "      inner join cgs_und  on  cgs_und.z01_i_cgsund = sau_cgscorreto.s127_i_numcgs";
     $sql .= "      left  join familiamicroarea  on  familiamicroarea.sd35_i_codigo = cgs_und.z01_i_familiamicroarea";
     $sql .= "      inner join cgs  as a on   a.z01_i_numcgs = cgs_und.z01_i_cgsund";
     $sql2 = "";
     if($dbwhere==""){
       if($s127_i_codigo!=null ){
         $sql2 .= " where sau_cgscorreto.s127_i_codigo = $s127_i_codigo "; 
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
   function sql_query_file ( $s127_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from sau_cgscorreto ";
     $sql2 = "";
     if($dbwhere==""){
       if($s127_i_codigo!=null ){
         $sql2 .= " where sau_cgscorreto.s127_i_codigo = $s127_i_codigo "; 
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