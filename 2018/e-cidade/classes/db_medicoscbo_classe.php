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
//CLASSE DA ENTIDADE medicoscbo
class cl_medicoscbo { 
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
   var $sd36_i_codigo = 0; 
   var $sd36_i_medico = 0; 
   var $sd36_i_rhcbo = 0; 
   var $sd36_c_vinculo = 0; 
   var $sd36_i_chambul = 0; 
   var $sd36_i_choutros = 0; 
   var $sd36_i_crm = 0; 
   var $sd36_c_crmuf = null; 
   var $sd36_c_crmorgao = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 sd36_i_codigo = int4 = Código 
                 sd36_i_medico = int4 = Médico 
                 sd36_i_rhcbo = int4 = CBO 
                 sd36_c_vinculo = int4 = Vínculo na Unidade 
                 sd36_i_chambul = int4 = Carga Horária Semanal (Ambulatorial) 
                 sd36_i_choutros = int4 = Carga Horária Semanal (Outros) 
                 sd36_i_crm = int8 = N° Registro Conselho de Classe 
                 sd36_c_crmuf = char(2) = UF do Registro 
                 sd36_c_crmorgao = char(50) = Órgão Emissor do Registro 
                 ";
   //funcao construtor da classe 
   function cl_medicoscbo() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("medicoscbo"); 
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
       $this->sd36_i_codigo = ($this->sd36_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd36_i_codigo"]:$this->sd36_i_codigo);
       $this->sd36_i_medico = ($this->sd36_i_medico == ""?@$GLOBALS["HTTP_POST_VARS"]["sd36_i_medico"]:$this->sd36_i_medico);
       $this->sd36_i_rhcbo = ($this->sd36_i_rhcbo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd36_i_rhcbo"]:$this->sd36_i_rhcbo);
       $this->sd36_c_vinculo = ($this->sd36_c_vinculo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd36_c_vinculo"]:$this->sd36_c_vinculo);
       $this->sd36_i_chambul = ($this->sd36_i_chambul == ""?@$GLOBALS["HTTP_POST_VARS"]["sd36_i_chambul"]:$this->sd36_i_chambul);
       $this->sd36_i_choutros = ($this->sd36_i_choutros == ""?@$GLOBALS["HTTP_POST_VARS"]["sd36_i_choutros"]:$this->sd36_i_choutros);
       $this->sd36_i_crm = ($this->sd36_i_crm == ""?@$GLOBALS["HTTP_POST_VARS"]["sd36_i_crm"]:$this->sd36_i_crm);
       $this->sd36_c_crmuf = ($this->sd36_c_crmuf == ""?@$GLOBALS["HTTP_POST_VARS"]["sd36_c_crmuf"]:$this->sd36_c_crmuf);
       $this->sd36_c_crmorgao = ($this->sd36_c_crmorgao == ""?@$GLOBALS["HTTP_POST_VARS"]["sd36_c_crmorgao"]:$this->sd36_c_crmorgao);
     }else{
       $this->sd36_i_codigo = ($this->sd36_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd36_i_codigo"]:$this->sd36_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($sd36_i_codigo){ 
      $this->atualizacampos();
     if($this->sd36_i_medico == null ){ 
       $this->erro_sql = " Campo Médico nao Informado.";
       $this->erro_campo = "sd36_i_medico";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd36_i_rhcbo == null ){ 
       $this->erro_sql = " Campo CBO nao Informado.";
       $this->erro_campo = "sd36_i_rhcbo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd36_c_vinculo == null ){ 
       $this->erro_sql = " Campo Vínculo na Unidade nao Informado.";
       $this->erro_campo = "sd36_c_vinculo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd36_i_chambul == null ){ 
       $this->sd36_i_chambul = "0";
     }
     if($this->sd36_i_choutros == null ){ 
       $this->sd36_i_choutros = "0";
     }
     if($this->sd36_i_crm == null ){ 
       $this->sd36_i_crm = "0";
     }
     if($sd36_i_codigo == "" || $sd36_i_codigo == null ){
       $result = db_query("select nextval('medicoscbo_sd36_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: medicoscbo_sd36_i_codigo_seq do campo: sd36_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->sd36_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from medicoscbo_sd36_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $sd36_i_codigo)){
         $this->erro_sql = " Campo sd36_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->sd36_i_codigo = $sd36_i_codigo; 
       }
     }
     if(($this->sd36_i_codigo == null) || ($this->sd36_i_codigo == "") ){ 
       $this->erro_sql = " Campo sd36_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into medicoscbo(
                                       sd36_i_codigo 
                                      ,sd36_i_medico 
                                      ,sd36_i_rhcbo 
                                      ,sd36_c_vinculo 
                                      ,sd36_i_chambul 
                                      ,sd36_i_choutros 
                                      ,sd36_i_crm 
                                      ,sd36_c_crmuf 
                                      ,sd36_c_crmorgao 
                       )
                values (
                                $this->sd36_i_codigo 
                               ,$this->sd36_i_medico 
                               ,$this->sd36_i_rhcbo 
                               ,$this->sd36_c_vinculo 
                               ,$this->sd36_i_chambul 
                               ,$this->sd36_i_choutros 
                               ,$this->sd36_i_crm 
                               ,'$this->sd36_c_crmuf' 
                               ,'$this->sd36_c_crmorgao' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "CBO dos Médicos ($this->sd36_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "CBO dos Médicos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "CBO dos Médicos ($this->sd36_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd36_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->sd36_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,11375,'$this->sd36_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1952,11375,'','".AddSlashes(pg_result($resaco,0,'sd36_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1952,11376,'','".AddSlashes(pg_result($resaco,0,'sd36_i_medico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1952,11377,'','".AddSlashes(pg_result($resaco,0,'sd36_i_rhcbo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1952,11378,'','".AddSlashes(pg_result($resaco,0,'sd36_c_vinculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1952,11379,'','".AddSlashes(pg_result($resaco,0,'sd36_i_chambul'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1952,11380,'','".AddSlashes(pg_result($resaco,0,'sd36_i_choutros'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1952,11381,'','".AddSlashes(pg_result($resaco,0,'sd36_i_crm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1952,11382,'','".AddSlashes(pg_result($resaco,0,'sd36_c_crmuf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1952,11383,'','".AddSlashes(pg_result($resaco,0,'sd36_c_crmorgao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($sd36_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update medicoscbo set ";
     $virgula = "";
     if(trim($this->sd36_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd36_i_codigo"])){ 
       $sql  .= $virgula." sd36_i_codigo = $this->sd36_i_codigo ";
       $virgula = ",";
       if(trim($this->sd36_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "sd36_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd36_i_medico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd36_i_medico"])){ 
       $sql  .= $virgula." sd36_i_medico = $this->sd36_i_medico ";
       $virgula = ",";
       if(trim($this->sd36_i_medico) == null ){ 
         $this->erro_sql = " Campo Médico nao Informado.";
         $this->erro_campo = "sd36_i_medico";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd36_i_rhcbo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd36_i_rhcbo"])){ 
       $sql  .= $virgula." sd36_i_rhcbo = $this->sd36_i_rhcbo ";
       $virgula = ",";
       if(trim($this->sd36_i_rhcbo) == null ){ 
         $this->erro_sql = " Campo CBO nao Informado.";
         $this->erro_campo = "sd36_i_rhcbo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd36_c_vinculo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd36_c_vinculo"])){ 
       $sql  .= $virgula." sd36_c_vinculo = $this->sd36_c_vinculo ";
       $virgula = ",";
       if(trim($this->sd36_c_vinculo) == null ){ 
         $this->erro_sql = " Campo Vínculo na Unidade nao Informado.";
         $this->erro_campo = "sd36_c_vinculo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd36_i_chambul)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd36_i_chambul"])){ 
        if(trim($this->sd36_i_chambul)=="" && isset($GLOBALS["HTTP_POST_VARS"]["sd36_i_chambul"])){ 
           $this->sd36_i_chambul = "0" ; 
        } 
       $sql  .= $virgula." sd36_i_chambul = $this->sd36_i_chambul ";
       $virgula = ",";
     }
     if(trim($this->sd36_i_choutros)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd36_i_choutros"])){ 
        if(trim($this->sd36_i_choutros)=="" && isset($GLOBALS["HTTP_POST_VARS"]["sd36_i_choutros"])){ 
           $this->sd36_i_choutros = "0" ; 
        } 
       $sql  .= $virgula." sd36_i_choutros = $this->sd36_i_choutros ";
       $virgula = ",";
     }
     if(trim($this->sd36_i_crm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd36_i_crm"])){ 
        if(trim($this->sd36_i_crm)=="" && isset($GLOBALS["HTTP_POST_VARS"]["sd36_i_crm"])){ 
           $this->sd36_i_crm = "0" ; 
        } 
       $sql  .= $virgula." sd36_i_crm = $this->sd36_i_crm ";
       $virgula = ",";
     }
     if(trim($this->sd36_c_crmuf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd36_c_crmuf"])){ 
       $sql  .= $virgula." sd36_c_crmuf = '$this->sd36_c_crmuf' ";
       $virgula = ",";
     }
     if(trim($this->sd36_c_crmorgao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd36_c_crmorgao"])){ 
       $sql  .= $virgula." sd36_c_crmorgao = '$this->sd36_c_crmorgao' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($sd36_i_codigo!=null){
       $sql .= " sd36_i_codigo = $this->sd36_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->sd36_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11375,'$this->sd36_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd36_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1952,11375,'".AddSlashes(pg_result($resaco,$conresaco,'sd36_i_codigo'))."','$this->sd36_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd36_i_medico"]))
           $resac = db_query("insert into db_acount values($acount,1952,11376,'".AddSlashes(pg_result($resaco,$conresaco,'sd36_i_medico'))."','$this->sd36_i_medico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd36_i_rhcbo"]))
           $resac = db_query("insert into db_acount values($acount,1952,11377,'".AddSlashes(pg_result($resaco,$conresaco,'sd36_i_rhcbo'))."','$this->sd36_i_rhcbo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd36_c_vinculo"]))
           $resac = db_query("insert into db_acount values($acount,1952,11378,'".AddSlashes(pg_result($resaco,$conresaco,'sd36_c_vinculo'))."','$this->sd36_c_vinculo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd36_i_chambul"]))
           $resac = db_query("insert into db_acount values($acount,1952,11379,'".AddSlashes(pg_result($resaco,$conresaco,'sd36_i_chambul'))."','$this->sd36_i_chambul',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd36_i_choutros"]))
           $resac = db_query("insert into db_acount values($acount,1952,11380,'".AddSlashes(pg_result($resaco,$conresaco,'sd36_i_choutros'))."','$this->sd36_i_choutros',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd36_i_crm"]))
           $resac = db_query("insert into db_acount values($acount,1952,11381,'".AddSlashes(pg_result($resaco,$conresaco,'sd36_i_crm'))."','$this->sd36_i_crm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd36_c_crmuf"]))
           $resac = db_query("insert into db_acount values($acount,1952,11382,'".AddSlashes(pg_result($resaco,$conresaco,'sd36_c_crmuf'))."','$this->sd36_c_crmuf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd36_c_crmorgao"]))
           $resac = db_query("insert into db_acount values($acount,1952,11383,'".AddSlashes(pg_result($resaco,$conresaco,'sd36_c_crmorgao'))."','$this->sd36_c_crmorgao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "CBO dos Médicos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd36_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "CBO dos Médicos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd36_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd36_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($sd36_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($sd36_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11375,'$sd36_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1952,11375,'','".AddSlashes(pg_result($resaco,$iresaco,'sd36_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1952,11376,'','".AddSlashes(pg_result($resaco,$iresaco,'sd36_i_medico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1952,11377,'','".AddSlashes(pg_result($resaco,$iresaco,'sd36_i_rhcbo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1952,11378,'','".AddSlashes(pg_result($resaco,$iresaco,'sd36_c_vinculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1952,11379,'','".AddSlashes(pg_result($resaco,$iresaco,'sd36_i_chambul'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1952,11380,'','".AddSlashes(pg_result($resaco,$iresaco,'sd36_i_choutros'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1952,11381,'','".AddSlashes(pg_result($resaco,$iresaco,'sd36_i_crm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1952,11382,'','".AddSlashes(pg_result($resaco,$iresaco,'sd36_c_crmuf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1952,11383,'','".AddSlashes(pg_result($resaco,$iresaco,'sd36_c_crmorgao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from medicoscbo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($sd36_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " sd36_i_codigo = $sd36_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "CBO dos Médicos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$sd36_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "CBO dos Médicos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$sd36_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$sd36_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:medicoscbo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $sd36_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from medicoscbo ";
     $sql .= "      inner join rhcbo  on  rhcbo.rh70_sequencial = medicoscbo.sd36_i_rhcbo";
     $sql .= "      inner join medicos  on  medicos.sd03_i_codigo = medicoscbo.sd36_i_medico";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = medicos.sd03_i_cgm";
     $sql2 = "";
     if($dbwhere==""){
       if($sd36_i_codigo!=null ){
         $sql2 .= " where medicoscbo.sd36_i_codigo = $sd36_i_codigo "; 
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
   function sql_query_file ( $sd36_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from medicoscbo ";
     $sql2 = "";
     if($dbwhere==""){
       if($sd36_i_codigo!=null ){
         $sql2 .= " where medicoscbo.sd36_i_codigo = $sd36_i_codigo "; 
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