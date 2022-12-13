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

//MODULO: saude
//CLASSE DA ENTIDADE agendtransf
class cl_agendtransf { 
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
   var $sd31_i_codigo = 0; 
   var $sd31_d_dataorigem_dia = null; 
   var $sd31_d_dataorigem_mes = null; 
   var $sd31_d_dataorigem_ano = null; 
   var $sd31_d_dataorigem = null; 
   var $sd31_i_agendamento = 0; 
   var $sd31_i_usuario = 0; 
   var $sd31_i_undmedorigem = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 sd31_i_codigo = int4 = Código 
                 sd31_d_dataorigem = date = Origem 
                 sd31_i_agendamento = int4 = Agendamento 
                 sd31_i_usuario = int4 = Usuário 
                 sd31_i_undmedorigem = int4 = Unidade / Médico 
                 ";
   //funcao construtor da classe 
   function cl_agendtransf() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("agendtransf"); 
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
       $this->sd31_i_codigo = ($this->sd31_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd31_i_codigo"]:$this->sd31_i_codigo);
       if($this->sd31_d_dataorigem == ""){
         $this->sd31_d_dataorigem_dia = ($this->sd31_d_dataorigem_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["sd31_d_dataorigem_dia"]:$this->sd31_d_dataorigem_dia);
         $this->sd31_d_dataorigem_mes = ($this->sd31_d_dataorigem_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["sd31_d_dataorigem_mes"]:$this->sd31_d_dataorigem_mes);
         $this->sd31_d_dataorigem_ano = ($this->sd31_d_dataorigem_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["sd31_d_dataorigem_ano"]:$this->sd31_d_dataorigem_ano);
         if($this->sd31_d_dataorigem_dia != ""){
            $this->sd31_d_dataorigem = $this->sd31_d_dataorigem_ano."-".$this->sd31_d_dataorigem_mes."-".$this->sd31_d_dataorigem_dia;
         }
       }
       $this->sd31_i_agendamento = ($this->sd31_i_agendamento == ""?@$GLOBALS["HTTP_POST_VARS"]["sd31_i_agendamento"]:$this->sd31_i_agendamento);
       $this->sd31_i_usuario = ($this->sd31_i_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["sd31_i_usuario"]:$this->sd31_i_usuario);
       $this->sd31_i_undmedorigem = ($this->sd31_i_undmedorigem == ""?@$GLOBALS["HTTP_POST_VARS"]["sd31_i_undmedorigem"]:$this->sd31_i_undmedorigem);
     }else{
       $this->sd31_i_codigo = ($this->sd31_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd31_i_codigo"]:$this->sd31_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($sd31_i_codigo){ 
      $this->atualizacampos();
     if($this->sd31_d_dataorigem == null ){ 
       $this->erro_sql = " Campo Origem nao Informado.";
       $this->erro_campo = "sd31_d_dataorigem_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd31_i_agendamento == null ){ 
       $this->erro_sql = " Campo Agendamento nao Informado.";
       $this->erro_campo = "sd31_i_agendamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd31_i_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "sd31_i_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd31_i_undmedorigem == null ){ 
       $this->erro_sql = " Campo Unidade / Médico nao Informado.";
       $this->erro_campo = "sd31_i_undmedorigem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($sd31_i_codigo == "" || $sd31_i_codigo == null ){
       $result = db_query("select nextval('agendtransf_sd31_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: agendtransf_sd31_i_codigo_seq do campo: sd31_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->sd31_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from agendtransf_sd31_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $sd31_i_codigo)){
         $this->erro_sql = " Campo sd31_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->sd31_i_codigo = $sd31_i_codigo; 
       }
     }
     if(($this->sd31_i_codigo == null) || ($this->sd31_i_codigo == "") ){ 
       $this->erro_sql = " Campo sd31_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into agendtransf(
                                       sd31_i_codigo 
                                      ,sd31_d_dataorigem 
                                      ,sd31_i_agendamento 
                                      ,sd31_i_usuario 
                                      ,sd31_i_undmedorigem 
                       )
                values (
                                $this->sd31_i_codigo 
                               ,".($this->sd31_d_dataorigem == "null" || $this->sd31_d_dataorigem == ""?"null":"'".$this->sd31_d_dataorigem."'")." 
                               ,$this->sd31_i_agendamento 
                               ,$this->sd31_i_usuario 
                               ,$this->sd31_i_undmedorigem 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Transferências ($this->sd31_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Transferências já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Transferências ($this->sd31_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd31_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->sd31_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1008834,'$this->sd31_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1010141,1008834,'','".AddSlashes(pg_result($resaco,0,'sd31_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010141,1008835,'','".AddSlashes(pg_result($resaco,0,'sd31_d_dataorigem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010141,1008836,'','".AddSlashes(pg_result($resaco,0,'sd31_i_agendamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010141,1008837,'','".AddSlashes(pg_result($resaco,0,'sd31_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010141,1008838,'','".AddSlashes(pg_result($resaco,0,'sd31_i_undmedorigem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($sd31_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update agendtransf set ";
     $virgula = "";
     if(trim($this->sd31_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd31_i_codigo"])){ 
       $sql  .= $virgula." sd31_i_codigo = $this->sd31_i_codigo ";
       $virgula = ",";
       if(trim($this->sd31_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "sd31_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd31_d_dataorigem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd31_d_dataorigem_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["sd31_d_dataorigem_dia"] !="") ){ 
       $sql  .= $virgula." sd31_d_dataorigem = '$this->sd31_d_dataorigem' ";
       $virgula = ",";
       if(trim($this->sd31_d_dataorigem) == null ){ 
         $this->erro_sql = " Campo Origem nao Informado.";
         $this->erro_campo = "sd31_d_dataorigem_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["sd31_d_dataorigem_dia"])){ 
         $sql  .= $virgula." sd31_d_dataorigem = null ";
         $virgula = ",";
         if(trim($this->sd31_d_dataorigem) == null ){ 
           $this->erro_sql = " Campo Origem nao Informado.";
           $this->erro_campo = "sd31_d_dataorigem_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->sd31_i_agendamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd31_i_agendamento"])){ 
       $sql  .= $virgula." sd31_i_agendamento = $this->sd31_i_agendamento ";
       $virgula = ",";
       if(trim($this->sd31_i_agendamento) == null ){ 
         $this->erro_sql = " Campo Agendamento nao Informado.";
         $this->erro_campo = "sd31_i_agendamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd31_i_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd31_i_usuario"])){ 
       $sql  .= $virgula." sd31_i_usuario = $this->sd31_i_usuario ";
       $virgula = ",";
       if(trim($this->sd31_i_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "sd31_i_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd31_i_undmedorigem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd31_i_undmedorigem"])){ 
       $sql  .= $virgula." sd31_i_undmedorigem = $this->sd31_i_undmedorigem ";
       $virgula = ",";
       if(trim($this->sd31_i_undmedorigem) == null ){ 
         $this->erro_sql = " Campo Unidade / Médico nao Informado.";
         $this->erro_campo = "sd31_i_undmedorigem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($sd31_i_codigo!=null){
       $sql .= " sd31_i_codigo = $this->sd31_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->sd31_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008834,'$this->sd31_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd31_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1010141,1008834,'".AddSlashes(pg_result($resaco,$conresaco,'sd31_i_codigo'))."','$this->sd31_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd31_d_dataorigem"]))
           $resac = db_query("insert into db_acount values($acount,1010141,1008835,'".AddSlashes(pg_result($resaco,$conresaco,'sd31_d_dataorigem'))."','$this->sd31_d_dataorigem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd31_i_agendamento"]))
           $resac = db_query("insert into db_acount values($acount,1010141,1008836,'".AddSlashes(pg_result($resaco,$conresaco,'sd31_i_agendamento'))."','$this->sd31_i_agendamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd31_i_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1010141,1008837,'".AddSlashes(pg_result($resaco,$conresaco,'sd31_i_usuario'))."','$this->sd31_i_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd31_i_undmedorigem"]))
           $resac = db_query("insert into db_acount values($acount,1010141,1008838,'".AddSlashes(pg_result($resaco,$conresaco,'sd31_i_undmedorigem'))."','$this->sd31_i_undmedorigem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Transferências nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd31_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Transferências nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd31_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd31_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($sd31_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($sd31_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008834,'$sd31_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1010141,1008834,'','".AddSlashes(pg_result($resaco,$iresaco,'sd31_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010141,1008835,'','".AddSlashes(pg_result($resaco,$iresaco,'sd31_d_dataorigem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010141,1008836,'','".AddSlashes(pg_result($resaco,$iresaco,'sd31_i_agendamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010141,1008837,'','".AddSlashes(pg_result($resaco,$iresaco,'sd31_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010141,1008838,'','".AddSlashes(pg_result($resaco,$iresaco,'sd31_i_undmedorigem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from agendtransf
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($sd31_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " sd31_i_codigo = $sd31_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Transferências nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$sd31_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Transferências nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$sd31_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$sd31_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:agendtransf";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $sd31_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from agendtransf ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = agendtransf.sd31_i_usuario";
     $sql .= "      inner join agendamentos  on  agendamentos.sd23_i_codigo = agendtransf.sd31_i_agendamento";
     $sql .= "      inner join unidademedicos  on  unidademedicos.sd04_i_codigo = agendtransf.sd31_i_undmedorigem";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = agendamentos.sd23_i_numcgm";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = agendamentos.sd23_i_usuario";
     $sql .= "      inner join unidademedicos  as a on   a.sd04_i_codigo = agendamentos.sd23_i_unidmed";
     $sql .= "      inner join unidades  on  unidades.sd02_i_codigo = unidademedicos.sd04_i_unidade";
     $sql .= "      inner join medicos  on  medicos.sd03_i_codigo = unidademedicos.sd04_i_medico";
     $sql2 = "";
     if($dbwhere==""){
       if($sd31_i_codigo!=null ){
         $sql2 .= " where agendtransf.sd31_i_codigo = $sd31_i_codigo "; 
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
   function sql_query_file ( $sd31_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from agendtransf ";
     $sql2 = "";
     if($dbwhere==""){
       if($sd31_i_codigo!=null ){
         $sql2 .= " where agendtransf.sd31_i_codigo = $sd31_i_codigo "; 
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