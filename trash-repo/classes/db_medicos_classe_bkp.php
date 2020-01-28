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
//CLASSE DA ENTIDADE medicos
class cl_medicos { 
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
   var $sd03_i_codigo = 0; 
   var $sd03_i_crm = 0; 
   var $sd03_d_ausencia1_dia = null; 
   var $sd03_d_ausencia1_mes = null; 
   var $sd03_d_ausencia1_ano = null; 
   var $sd03_d_ausencia1 = null; 
   var $sd03_d_ausencia2_dia = null; 
   var $sd03_d_ausencia2_mes = null; 
   var $sd03_d_ausencia2_ano = null; 
   var $sd03_d_ausencia2 = null; 
   var $sd03_i_numerodias = 0; 
   var $sd03_d_datacadastro_dia = null; 
   var $sd03_d_datacadastro_mes = null; 
   var $sd03_d_datacadastro_ano = null; 
   var $sd03_d_datacadastro = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 sd03_i_codigo = int8 = Código 
                 sd03_i_crm = int4 = Crm/Cro 
                 sd03_d_ausencia1 = date = Ausencia1 
                 sd03_d_ausencia2 = date = Ausencia2 
                 sd03_i_numerodias = int4 = Dias para Agendar 
                 sd03_d_datacadastro = date = Data de Cadastro 
                 ";
   //funcao construtor da classe 
   function cl_medicos() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("medicos"); 
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
       $this->sd03_i_codigo = ($this->sd03_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd03_i_codigo"]:$this->sd03_i_codigo);
       $this->sd03_i_crm = ($this->sd03_i_crm == ""?@$GLOBALS["HTTP_POST_VARS"]["sd03_i_crm"]:$this->sd03_i_crm);
       if($this->sd03_d_ausencia1 == ""){
         $this->sd03_d_ausencia1_dia = ($this->sd03_d_ausencia1_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["sd03_d_ausencia1_dia"]:$this->sd03_d_ausencia1_dia);
         $this->sd03_d_ausencia1_mes = ($this->sd03_d_ausencia1_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["sd03_d_ausencia1_mes"]:$this->sd03_d_ausencia1_mes);
         $this->sd03_d_ausencia1_ano = ($this->sd03_d_ausencia1_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["sd03_d_ausencia1_ano"]:$this->sd03_d_ausencia1_ano);
         if($this->sd03_d_ausencia1_dia != ""){
            $this->sd03_d_ausencia1 = $this->sd03_d_ausencia1_ano."-".$this->sd03_d_ausencia1_mes."-".$this->sd03_d_ausencia1_dia;
         }
       }
       if($this->sd03_d_ausencia2 == ""){
         $this->sd03_d_ausencia2_dia = ($this->sd03_d_ausencia2_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["sd03_d_ausencia2_dia"]:$this->sd03_d_ausencia2_dia);
         $this->sd03_d_ausencia2_mes = ($this->sd03_d_ausencia2_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["sd03_d_ausencia2_mes"]:$this->sd03_d_ausencia2_mes);
         $this->sd03_d_ausencia2_ano = ($this->sd03_d_ausencia2_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["sd03_d_ausencia2_ano"]:$this->sd03_d_ausencia2_ano);
         if($this->sd03_d_ausencia2_dia != ""){
            $this->sd03_d_ausencia2 = $this->sd03_d_ausencia2_ano."-".$this->sd03_d_ausencia2_mes."-".$this->sd03_d_ausencia2_dia;
         }
       }
       $this->sd03_i_numerodias = ($this->sd03_i_numerodias == ""?@$GLOBALS["HTTP_POST_VARS"]["sd03_i_numerodias"]:$this->sd03_i_numerodias);
       if($this->sd03_d_datacadastro == ""){
         $this->sd03_d_datacadastro_dia = ($this->sd03_d_datacadastro_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["sd03_d_datacadastro_dia"]:$this->sd03_d_datacadastro_dia);
         $this->sd03_d_datacadastro_mes = ($this->sd03_d_datacadastro_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["sd03_d_datacadastro_mes"]:$this->sd03_d_datacadastro_mes);
         $this->sd03_d_datacadastro_ano = ($this->sd03_d_datacadastro_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["sd03_d_datacadastro_ano"]:$this->sd03_d_datacadastro_ano);
         if($this->sd03_d_datacadastro_dia != ""){
            $this->sd03_d_datacadastro = $this->sd03_d_datacadastro_ano."-".$this->sd03_d_datacadastro_mes."-".$this->sd03_d_datacadastro_dia;
         }
       }
     }else{
       $this->sd03_i_codigo = ($this->sd03_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd03_i_codigo"]:$this->sd03_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($sd03_i_codigo){ 
      $this->atualizacampos();
     if($this->sd03_i_crm == null ){ 
       $this->erro_sql = " Campo Crm/Cro nao Informado.";
       $this->erro_campo = "sd03_i_crm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd03_d_ausencia1 == null ){ 
       $this->erro_sql = " Campo Ausencia1 nao Informado.";
       $this->erro_campo = "sd03_d_ausencia1_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd03_d_ausencia2 == null ){ 
       $this->erro_sql = " Campo Ausencia2 nao Informado.";
       $this->erro_campo = "sd03_d_ausencia2_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd03_i_numerodias == null ){ 
       $this->erro_sql = " Campo Dias para Agendar nao Informado.";
       $this->erro_campo = "sd03_i_numerodias";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd03_d_datacadastro == null ){ 
       $this->erro_sql = " Campo Data de Cadastro nao Informado.";
       $this->erro_campo = "sd03_d_datacadastro_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->sd03_i_codigo = $sd03_i_codigo; 
     if(($this->sd03_i_codigo == null) || ($this->sd03_i_codigo == "") ){ 
       $this->erro_sql = " Campo sd03_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into medicos(
                                       sd03_i_codigo 
                                      ,sd03_i_crm 
                                      ,sd03_d_ausencia1 
                                      ,sd03_d_ausencia2 
                                      ,sd03_i_numerodias 
                                      ,sd03_d_datacadastro 
                       )
                values (
                                $this->sd03_i_codigo 
                               ,$this->sd03_i_crm 
                               ,".($this->sd03_d_ausencia1 == "null" || $this->sd03_d_ausencia1 == ""?"null":"'".$this->sd03_d_ausencia1."'")." 
                               ,".($this->sd03_d_ausencia2 == "null" || $this->sd03_d_ausencia2 == ""?"null":"'".$this->sd03_d_ausencia2."'")." 
                               ,$this->sd03_i_numerodias 
                               ,".($this->sd03_d_datacadastro == "null" || $this->sd03_d_datacadastro == ""?"null":"'".$this->sd03_d_datacadastro."'")." 
                      )";
     $result = @pg_exec($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Médicos ($this->sd03_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Médicos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Médicos ($this->sd03_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd03_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->sd03_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,100051,'$this->sd03_i_codigo','I')");
       $resac = pg_query("insert into db_acount values($acount,100012,100051,'','".AddSlashes(pg_result($resaco,0,'sd03_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,100012,100070,'','".AddSlashes(pg_result($resaco,0,'sd03_i_crm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,100012,100078,'','".AddSlashes(pg_result($resaco,0,'sd03_d_ausencia1'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,100012,100079,'','".AddSlashes(pg_result($resaco,0,'sd03_d_ausencia2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,100012,100077,'','".AddSlashes(pg_result($resaco,0,'sd03_i_numerodias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,100012,100080,'','".AddSlashes(pg_result($resaco,0,'sd03_d_datacadastro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($sd03_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update medicos set ";
     $virgula = "";
     if(trim($this->sd03_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd03_i_codigo"])){ 
       $sql  .= $virgula." sd03_i_codigo = $this->sd03_i_codigo ";
       $virgula = ",";
       if(trim($this->sd03_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "sd03_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd03_i_crm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd03_i_crm"])){ 
       $sql  .= $virgula." sd03_i_crm = $this->sd03_i_crm ";
       $virgula = ",";
       if(trim($this->sd03_i_crm) == null ){ 
         $this->erro_sql = " Campo Crm/Cro nao Informado.";
         $this->erro_campo = "sd03_i_crm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd03_d_ausencia1)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd03_d_ausencia1_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["sd03_d_ausencia1_dia"] !="") ){ 
       $sql  .= $virgula." sd03_d_ausencia1 = '$this->sd03_d_ausencia1' ";
       $virgula = ",";
       if(trim($this->sd03_d_ausencia1) == null ){ 
         $this->erro_sql = " Campo Ausencia1 nao Informado.";
         $this->erro_campo = "sd03_d_ausencia1_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["sd03_d_ausencia1_dia"])){ 
         $sql  .= $virgula." sd03_d_ausencia1 = null ";
         $virgula = ",";
         if(trim($this->sd03_d_ausencia1) == null ){ 
           $this->erro_sql = " Campo Ausencia1 nao Informado.";
           $this->erro_campo = "sd03_d_ausencia1_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->sd03_d_ausencia2)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd03_d_ausencia2_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["sd03_d_ausencia2_dia"] !="") ){ 
       $sql  .= $virgula." sd03_d_ausencia2 = '$this->sd03_d_ausencia2' ";
       $virgula = ",";
       if(trim($this->sd03_d_ausencia2) == null ){ 
         $this->erro_sql = " Campo Ausencia2 nao Informado.";
         $this->erro_campo = "sd03_d_ausencia2_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["sd03_d_ausencia2_dia"])){ 
         $sql  .= $virgula." sd03_d_ausencia2 = null ";
         $virgula = ",";
         if(trim($this->sd03_d_ausencia2) == null ){ 
           $this->erro_sql = " Campo Ausencia2 nao Informado.";
           $this->erro_campo = "sd03_d_ausencia2_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->sd03_i_numerodias)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd03_i_numerodias"])){ 
       $sql  .= $virgula." sd03_i_numerodias = $this->sd03_i_numerodias ";
       $virgula = ",";
       if(trim($this->sd03_i_numerodias) == null ){ 
         $this->erro_sql = " Campo Dias para Agendar nao Informado.";
         $this->erro_campo = "sd03_i_numerodias";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd03_d_datacadastro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd03_d_datacadastro_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["sd03_d_datacadastro_dia"] !="") ){ 
       $sql  .= $virgula." sd03_d_datacadastro = '$this->sd03_d_datacadastro' ";
       $virgula = ",";
       if(trim($this->sd03_d_datacadastro) == null ){ 
         $this->erro_sql = " Campo Data de Cadastro nao Informado.";
         $this->erro_campo = "sd03_d_datacadastro_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["sd03_d_datacadastro_dia"])){ 
         $sql  .= $virgula." sd03_d_datacadastro = null ";
         $virgula = ",";
         if(trim($this->sd03_d_datacadastro) == null ){ 
           $this->erro_sql = " Campo Data de Cadastro nao Informado.";
           $this->erro_campo = "sd03_d_datacadastro_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($sd03_i_codigo!=null){
       $sql .= " sd03_i_codigo = $this->sd03_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->sd03_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,100051,'$this->sd03_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd03_i_codigo"]))
           $resac = pg_query("insert into db_acount values($acount,100012,100051,'".AddSlashes(pg_result($resaco,$conresaco,'sd03_i_codigo'))."','$this->sd03_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd03_i_crm"]))
           $resac = pg_query("insert into db_acount values($acount,100012,100070,'".AddSlashes(pg_result($resaco,$conresaco,'sd03_i_crm'))."','$this->sd03_i_crm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd03_d_ausencia1"]))
           $resac = pg_query("insert into db_acount values($acount,100012,100078,'".AddSlashes(pg_result($resaco,$conresaco,'sd03_d_ausencia1'))."','$this->sd03_d_ausencia1',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd03_d_ausencia2"]))
           $resac = pg_query("insert into db_acount values($acount,100012,100079,'".AddSlashes(pg_result($resaco,$conresaco,'sd03_d_ausencia2'))."','$this->sd03_d_ausencia2',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd03_i_numerodias"]))
           $resac = pg_query("insert into db_acount values($acount,100012,100077,'".AddSlashes(pg_result($resaco,$conresaco,'sd03_i_numerodias'))."','$this->sd03_i_numerodias',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd03_d_datacadastro"]))
           $resac = pg_query("insert into db_acount values($acount,100012,100080,'".AddSlashes(pg_result($resaco,$conresaco,'sd03_d_datacadastro'))."','$this->sd03_d_datacadastro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = @pg_exec($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Médicos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd03_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Médicos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd03_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd03_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($sd03_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($sd03_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,100051,'$this->sd03_i_codigo','E')");
         $resac = pg_query("insert into db_acount values($acount,100012,100051,'','".AddSlashes(pg_result($resaco,$iresaco,'sd03_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,100012,100070,'','".AddSlashes(pg_result($resaco,$iresaco,'sd03_i_crm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,100012,100078,'','".AddSlashes(pg_result($resaco,$iresaco,'sd03_d_ausencia1'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,100012,100079,'','".AddSlashes(pg_result($resaco,$iresaco,'sd03_d_ausencia2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,100012,100077,'','".AddSlashes(pg_result($resaco,$iresaco,'sd03_i_numerodias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,100012,100080,'','".AddSlashes(pg_result($resaco,$iresaco,'sd03_d_datacadastro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from medicos
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($sd03_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " sd03_i_codigo = $sd03_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = @pg_exec($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Médicos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$sd03_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Médicos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$sd03_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$sd03_i_codigo;
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
     $result = @pg_query($sql);
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
        $this->erro_sql   = "Record Vazio na Tabela:medicos";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $sd03_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from medicos ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = medicos.sd03_i_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($sd03_i_codigo!=null ){
         $sql2 .= " where medicos.sd03_i_codigo = $sd03_i_codigo "; 
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
   function sql_query_file ( $sd03_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from medicos ";
     $sql2 = "";
     if($dbwhere==""){
       if($sd03_i_codigo!=null ){
         $sql2 .= " where medicos.sd03_i_codigo = $sd03_i_codigo "; 
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