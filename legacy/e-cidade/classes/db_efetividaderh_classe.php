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

//MODULO: escola
//CLASSE DA ENTIDADE efetividaderh
class cl_efetividaderh { 
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
   var $ed98_i_codigo = 0; 
   var $ed98_i_escola = 0; 
   var $ed98_i_mes = 0; 
   var $ed98_i_ano = 0; 
   var $ed98_c_tipo = null; 
   var $ed98_d_dataini_dia = null; 
   var $ed98_d_dataini_mes = null; 
   var $ed98_d_dataini_ano = null; 
   var $ed98_d_dataini = null; 
   var $ed98_d_datafim_dia = null; 
   var $ed98_d_datafim_mes = null; 
   var $ed98_d_datafim_ano = null; 
   var $ed98_d_datafim = null; 
   var $ed98_c_tipocomp = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed98_i_codigo = int8 = Código 
                 ed98_i_escola = int8 = Escola 
                 ed98_i_mes = int4 = Mês Competência 
                 ed98_i_ano = int4 = Ano Competência 
                 ed98_c_tipo = char(1) = Efetividade de 
                 ed98_d_dataini = date = Data Inicial 
                 ed98_d_datafim = date = Data Final 
                 ed98_c_tipocomp = char(1) = Tipo de Competência 
                 ";
   //funcao construtor da classe 
   function cl_efetividaderh() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("efetividaderh"); 
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
       $this->ed98_i_codigo = ($this->ed98_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed98_i_codigo"]:$this->ed98_i_codigo);
       $this->ed98_i_escola = ($this->ed98_i_escola == ""?@$GLOBALS["HTTP_POST_VARS"]["ed98_i_escola"]:$this->ed98_i_escola);
       $this->ed98_i_mes = ($this->ed98_i_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed98_i_mes"]:$this->ed98_i_mes);
       $this->ed98_i_ano = ($this->ed98_i_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed98_i_ano"]:$this->ed98_i_ano);
       $this->ed98_c_tipo = ($this->ed98_c_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed98_c_tipo"]:$this->ed98_c_tipo);
       if($this->ed98_d_dataini == ""){
         $this->ed98_d_dataini_dia = ($this->ed98_d_dataini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed98_d_dataini_dia"]:$this->ed98_d_dataini_dia);
         $this->ed98_d_dataini_mes = ($this->ed98_d_dataini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed98_d_dataini_mes"]:$this->ed98_d_dataini_mes);
         $this->ed98_d_dataini_ano = ($this->ed98_d_dataini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed98_d_dataini_ano"]:$this->ed98_d_dataini_ano);
         if($this->ed98_d_dataini_dia != ""){
            $this->ed98_d_dataini = $this->ed98_d_dataini_ano."-".$this->ed98_d_dataini_mes."-".$this->ed98_d_dataini_dia;
         }
       }
       if($this->ed98_d_datafim == ""){
         $this->ed98_d_datafim_dia = ($this->ed98_d_datafim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed98_d_datafim_dia"]:$this->ed98_d_datafim_dia);
         $this->ed98_d_datafim_mes = ($this->ed98_d_datafim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed98_d_datafim_mes"]:$this->ed98_d_datafim_mes);
         $this->ed98_d_datafim_ano = ($this->ed98_d_datafim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed98_d_datafim_ano"]:$this->ed98_d_datafim_ano);
         if($this->ed98_d_datafim_dia != ""){
            $this->ed98_d_datafim = $this->ed98_d_datafim_ano."-".$this->ed98_d_datafim_mes."-".$this->ed98_d_datafim_dia;
         }
       }
       $this->ed98_c_tipocomp = ($this->ed98_c_tipocomp == ""?@$GLOBALS["HTTP_POST_VARS"]["ed98_c_tipocomp"]:$this->ed98_c_tipocomp);
     }else{
       $this->ed98_i_codigo = ($this->ed98_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed98_i_codigo"]:$this->ed98_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed98_i_codigo){ 
      $this->atualizacampos();
     if($this->ed98_i_escola == null ){ 
       $this->erro_sql = " Campo Escola nao Informado.";
       $this->erro_campo = "ed98_i_escola";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed98_i_mes == null ){ 
       $this->ed98_i_mes = "null";
     }
     if($this->ed98_i_ano == null ){ 
       $this->ed98_i_ano = "null";
     }
     if($this->ed98_c_tipo == null ){ 
       $this->erro_sql = " Campo Efetividade de nao Informado.";
       $this->erro_campo = "ed98_c_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed98_d_dataini == null ){ 
       $this->erro_sql = " Campo Data Inicial nao Informado.";
       $this->erro_campo = "ed98_d_dataini_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed98_d_datafim == null ){ 
       $this->erro_sql = " Campo Data Final nao Informado.";
       $this->erro_campo = "ed98_d_datafim_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed98_c_tipocomp == null ){ 
       $this->erro_sql = " Campo Tipo de Competência nao Informado.";
       $this->erro_campo = "ed98_c_tipocomp";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed98_i_codigo == "" || $ed98_i_codigo == null ){
       $result = db_query("select nextval('efetividaderh_ed98_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: efetividaderh_ed98_i_codigo_seq do campo: ed98_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed98_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from efetividaderh_ed98_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed98_i_codigo)){
         $this->erro_sql = " Campo ed98_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed98_i_codigo = $ed98_i_codigo; 
       }
     }
     if(($this->ed98_i_codigo == null) || ($this->ed98_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed98_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into efetividaderh(
                                       ed98_i_codigo 
                                      ,ed98_i_escola 
                                      ,ed98_i_mes 
                                      ,ed98_i_ano 
                                      ,ed98_c_tipo 
                                      ,ed98_d_dataini 
                                      ,ed98_d_datafim 
                                      ,ed98_c_tipocomp 
                       )
                values (
                                $this->ed98_i_codigo 
                               ,$this->ed98_i_escola 
                               ,$this->ed98_i_mes 
                               ,$this->ed98_i_ano 
                               ,'$this->ed98_c_tipo' 
                               ,".($this->ed98_d_dataini == "null" || $this->ed98_d_dataini == ""?"null":"'".$this->ed98_d_dataini."'")." 
                               ,".($this->ed98_d_datafim == "null" || $this->ed98_d_datafim == ""?"null":"'".$this->ed98_d_datafim."'")." 
                               ,'$this->ed98_c_tipocomp' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro do Periodo da Efetividade ($this->ed98_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro do Periodo da Efetividade já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro do Periodo da Efetividade ($this->ed98_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed98_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed98_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1009003,'$this->ed98_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1010156,1009003,'','".AddSlashes(pg_result($resaco,0,'ed98_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010156,1009004,'','".AddSlashes(pg_result($resaco,0,'ed98_i_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010156,1009001,'','".AddSlashes(pg_result($resaco,0,'ed98_i_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010156,1009002,'','".AddSlashes(pg_result($resaco,0,'ed98_i_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010156,1009005,'','".AddSlashes(pg_result($resaco,0,'ed98_c_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010156,14557,'','".AddSlashes(pg_result($resaco,0,'ed98_d_dataini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010156,14558,'','".AddSlashes(pg_result($resaco,0,'ed98_d_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010156,14559,'','".AddSlashes(pg_result($resaco,0,'ed98_c_tipocomp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed98_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update efetividaderh set ";
     $virgula = "";
     if(trim($this->ed98_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed98_i_codigo"])){ 
       $sql  .= $virgula." ed98_i_codigo = $this->ed98_i_codigo ";
       $virgula = ",";
       if(trim($this->ed98_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed98_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed98_i_escola)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed98_i_escola"])){ 
       $sql  .= $virgula." ed98_i_escola = $this->ed98_i_escola ";
       $virgula = ",";
       if(trim($this->ed98_i_escola) == null ){ 
         $this->erro_sql = " Campo Escola nao Informado.";
         $this->erro_campo = "ed98_i_escola";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed98_i_mes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed98_i_mes"])){ 
        if(trim($this->ed98_i_mes)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed98_i_mes"])){ 
           $this->ed98_i_mes = "null" ;
        } 
       $sql  .= $virgula." ed98_i_mes = $this->ed98_i_mes ";
       $virgula = ",";
     }
     if(trim($this->ed98_i_ano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed98_i_ano"])){ 
        if(trim($this->ed98_i_ano)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed98_i_ano"])){ 
           $this->ed98_i_ano = "null" ;
        } 
       $sql  .= $virgula." ed98_i_ano = $this->ed98_i_ano ";
       $virgula = ",";
     }
     if(trim($this->ed98_c_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed98_c_tipo"])){ 
       $sql  .= $virgula." ed98_c_tipo = '$this->ed98_c_tipo' ";
       $virgula = ",";
       if(trim($this->ed98_c_tipo) == null ){ 
         $this->erro_sql = " Campo Efetividade de nao Informado.";
         $this->erro_campo = "ed98_c_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed98_d_dataini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed98_d_dataini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed98_d_dataini_dia"] !="") ){ 
       $sql  .= $virgula." ed98_d_dataini = '$this->ed98_d_dataini' ";
       $virgula = ",";
       if(trim($this->ed98_d_dataini) == null ){ 
         $this->erro_sql = " Campo Data Inicial nao Informado.";
         $this->erro_campo = "ed98_d_dataini_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed98_d_dataini_dia"])){ 
         $sql  .= $virgula." ed98_d_dataini = null ";
         $virgula = ",";
         if(trim($this->ed98_d_dataini) == null ){ 
           $this->erro_sql = " Campo Data Inicial nao Informado.";
           $this->erro_campo = "ed98_d_dataini_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ed98_d_datafim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed98_d_datafim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed98_d_datafim_dia"] !="") ){ 
       $sql  .= $virgula." ed98_d_datafim = '$this->ed98_d_datafim' ";
       $virgula = ",";
       if(trim($this->ed98_d_datafim) == null ){ 
         $this->erro_sql = " Campo Data Final nao Informado.";
         $this->erro_campo = "ed98_d_datafim_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed98_d_datafim_dia"])){ 
         $sql  .= $virgula." ed98_d_datafim = null ";
         $virgula = ",";
         if(trim($this->ed98_d_datafim) == null ){ 
           $this->erro_sql = " Campo Data Final nao Informado.";
           $this->erro_campo = "ed98_d_datafim_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ed98_c_tipocomp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed98_c_tipocomp"])){ 
       $sql  .= $virgula." ed98_c_tipocomp = '$this->ed98_c_tipocomp' ";
       $virgula = ",";
       if(trim($this->ed98_c_tipocomp) == null ){ 
         $this->erro_sql = " Campo Tipo de Competência nao Informado.";
         $this->erro_campo = "ed98_c_tipocomp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed98_i_codigo!=null){
       $sql .= " ed98_i_codigo = $this->ed98_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed98_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1009003,'$this->ed98_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed98_i_codigo"]) || $this->ed98_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,1010156,1009003,'".AddSlashes(pg_result($resaco,$conresaco,'ed98_i_codigo'))."','$this->ed98_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed98_i_escola"]) || $this->ed98_i_escola != "")
           $resac = db_query("insert into db_acount values($acount,1010156,1009004,'".AddSlashes(pg_result($resaco,$conresaco,'ed98_i_escola'))."','$this->ed98_i_escola',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed98_i_mes"]) || $this->ed98_i_mes != "")
           $resac = db_query("insert into db_acount values($acount,1010156,1009001,'".AddSlashes(pg_result($resaco,$conresaco,'ed98_i_mes'))."','$this->ed98_i_mes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed98_i_ano"]) || $this->ed98_i_ano != "")
           $resac = db_query("insert into db_acount values($acount,1010156,1009002,'".AddSlashes(pg_result($resaco,$conresaco,'ed98_i_ano'))."','$this->ed98_i_ano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed98_c_tipo"]) || $this->ed98_c_tipo != "")
           $resac = db_query("insert into db_acount values($acount,1010156,1009005,'".AddSlashes(pg_result($resaco,$conresaco,'ed98_c_tipo'))."','$this->ed98_c_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed98_d_dataini"]) || $this->ed98_d_dataini != "")
           $resac = db_query("insert into db_acount values($acount,1010156,14557,'".AddSlashes(pg_result($resaco,$conresaco,'ed98_d_dataini'))."','$this->ed98_d_dataini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed98_d_datafim"]) || $this->ed98_d_datafim != "")
           $resac = db_query("insert into db_acount values($acount,1010156,14558,'".AddSlashes(pg_result($resaco,$conresaco,'ed98_d_datafim'))."','$this->ed98_d_datafim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed98_c_tipocomp"]) || $this->ed98_c_tipocomp != "")
           $resac = db_query("insert into db_acount values($acount,1010156,14559,'".AddSlashes(pg_result($resaco,$conresaco,'ed98_c_tipocomp'))."','$this->ed98_c_tipocomp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro do Periodo da Efetividade nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed98_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro do Periodo da Efetividade nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed98_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed98_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed98_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed98_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1009003,'$ed98_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1010156,1009003,'','".AddSlashes(pg_result($resaco,$iresaco,'ed98_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010156,1009004,'','".AddSlashes(pg_result($resaco,$iresaco,'ed98_i_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010156,1009001,'','".AddSlashes(pg_result($resaco,$iresaco,'ed98_i_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010156,1009002,'','".AddSlashes(pg_result($resaco,$iresaco,'ed98_i_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010156,1009005,'','".AddSlashes(pg_result($resaco,$iresaco,'ed98_c_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010156,14557,'','".AddSlashes(pg_result($resaco,$iresaco,'ed98_d_dataini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010156,14558,'','".AddSlashes(pg_result($resaco,$iresaco,'ed98_d_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010156,14559,'','".AddSlashes(pg_result($resaco,$iresaco,'ed98_c_tipocomp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from efetividaderh
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed98_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed98_i_codigo = $ed98_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro do Periodo da Efetividade nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed98_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro do Periodo da Efetividade nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed98_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed98_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:efetividaderh";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed98_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from efetividaderh ";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = efetividaderh.ed98_i_escola";
     $sql .= "      inner join bairro  on  bairro.j13_codi = escola.ed18_i_bairro";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = escola.ed18_i_rua";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = escola.ed18_i_codigo";
     $sql .= "      inner join censouf  on  censouf.ed260_i_codigo = escola.ed18_i_censouf";
     $sql .= "      inner join censomunic  on  censomunic.ed261_i_codigo = escola.ed18_i_censomunic";
     $sql .= "      left  join censodistrito  on  censodistrito.ed262_i_codigo = escola.ed18_i_censodistrito";
     $sql .= "      left  join censoorgreg  on  censoorgreg.ed263_i_codigo = escola.ed18_i_censoorgreg";
     $sql .= "      left  join censolinguaindig  on  censolinguaindig.ed264_i_codigo = escola.ed18_i_linguaindigena";
     $sql2 = "";
     if($dbwhere==""){
       if($ed98_i_codigo!=null ){
         $sql2 .= " where efetividaderh.ed98_i_codigo = $ed98_i_codigo "; 
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
   function sql_query_file ( $ed98_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from efetividaderh ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed98_i_codigo!=null ){
         $sql2 .= " where efetividaderh.ed98_i_codigo = $ed98_i_codigo "; 
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