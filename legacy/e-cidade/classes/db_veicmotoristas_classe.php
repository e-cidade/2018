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
//CLASSE DA ENTIDADE veicmotoristas
class cl_veicmotoristas { 
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
   var $ve05_codigo = 0; 
   var $ve05_numcgm = 0; 
   var $ve05_habilitacao = null; 
   var $ve05_veiccadcategcnh = 0; 
   var $ve05_dtvenc_dia = null; 
   var $ve05_dtvenc_mes = null; 
   var $ve05_dtvenc_ano = null; 
   var $ve05_dtvenc = null; 
   var $ve05_dtprimcnh_dia = null; 
   var $ve05_dtprimcnh_mes = null; 
   var $ve05_dtprimcnh_ano = null; 
   var $ve05_dtprimcnh = null; 
   var $ve05_veiccadmotoristasit = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ve05_codigo = int4 = Código do Motorista 
                 ve05_numcgm = int4 = Motorista 
                 ve05_habilitacao = char(20) = Nº Habilitação 
                 ve05_veiccadcategcnh = int4 = Categoria CNH 
                 ve05_dtvenc = date = Validade 
                 ve05_dtprimcnh = date = Primeira Habilitação 
                 ve05_veiccadmotoristasit = int4 = Situação do Condutor 
                 ";
   //funcao construtor da classe 
   function cl_veicmotoristas() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("veicmotoristas"); 
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
       $this->ve05_codigo = ($this->ve05_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ve05_codigo"]:$this->ve05_codigo);
       $this->ve05_numcgm = ($this->ve05_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["ve05_numcgm"]:$this->ve05_numcgm);
       $this->ve05_habilitacao = ($this->ve05_habilitacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ve05_habilitacao"]:$this->ve05_habilitacao);
       $this->ve05_veiccadcategcnh = ($this->ve05_veiccadcategcnh == ""?@$GLOBALS["HTTP_POST_VARS"]["ve05_veiccadcategcnh"]:$this->ve05_veiccadcategcnh);
       if($this->ve05_dtvenc == ""){
         $this->ve05_dtvenc_dia = ($this->ve05_dtvenc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ve05_dtvenc_dia"]:$this->ve05_dtvenc_dia);
         $this->ve05_dtvenc_mes = ($this->ve05_dtvenc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ve05_dtvenc_mes"]:$this->ve05_dtvenc_mes);
         $this->ve05_dtvenc_ano = ($this->ve05_dtvenc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ve05_dtvenc_ano"]:$this->ve05_dtvenc_ano);
         if($this->ve05_dtvenc_dia != ""){
            $this->ve05_dtvenc = $this->ve05_dtvenc_ano."-".$this->ve05_dtvenc_mes."-".$this->ve05_dtvenc_dia;
         }
       }
       if($this->ve05_dtprimcnh == ""){
         $this->ve05_dtprimcnh_dia = ($this->ve05_dtprimcnh_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ve05_dtprimcnh_dia"]:$this->ve05_dtprimcnh_dia);
         $this->ve05_dtprimcnh_mes = ($this->ve05_dtprimcnh_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ve05_dtprimcnh_mes"]:$this->ve05_dtprimcnh_mes);
         $this->ve05_dtprimcnh_ano = ($this->ve05_dtprimcnh_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ve05_dtprimcnh_ano"]:$this->ve05_dtprimcnh_ano);
         if($this->ve05_dtprimcnh_dia != ""){
            $this->ve05_dtprimcnh = $this->ve05_dtprimcnh_ano."-".$this->ve05_dtprimcnh_mes."-".$this->ve05_dtprimcnh_dia;
         }
       }
       $this->ve05_veiccadmotoristasit = ($this->ve05_veiccadmotoristasit == ""?@$GLOBALS["HTTP_POST_VARS"]["ve05_veiccadmotoristasit"]:$this->ve05_veiccadmotoristasit);
     }else{
       $this->ve05_codigo = ($this->ve05_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ve05_codigo"]:$this->ve05_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ve05_codigo){ 
      $this->atualizacampos();
     if($this->ve05_numcgm == null ){ 
       $this->erro_sql = " Campo Motorista nao Informado.";
       $this->erro_campo = "ve05_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve05_habilitacao == null ){ 
       $this->erro_sql = " Campo Nº Habilitação nao Informado.";
       $this->erro_campo = "ve05_habilitacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve05_veiccadcategcnh == null ){ 
       $this->erro_sql = " Campo Categoria CNH nao Informado.";
       $this->erro_campo = "ve05_veiccadcategcnh";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve05_dtvenc == null ){ 
       $this->erro_sql = " Campo Validade nao Informado.";
       $this->erro_campo = "ve05_dtvenc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve05_dtprimcnh == null ){ 
       $this->erro_sql = " Campo Primeira Habilitação nao Informado.";
       $this->erro_campo = "ve05_dtprimcnh_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve05_veiccadmotoristasit == null ){ 
       $this->erro_sql = " Campo Situação do Condutor nao Informado.";
       $this->erro_campo = "ve05_veiccadmotoristasit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ve05_codigo == "" || $ve05_codigo == null ){
       $result = db_query("select nextval('veicmotoristas_ve05_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: veicmotoristas_ve05_codigo_seq do campo: ve05_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ve05_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from veicmotoristas_ve05_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ve05_codigo)){
         $this->erro_sql = " Campo ve05_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ve05_codigo = $ve05_codigo; 
       }
     }
     if(($this->ve05_codigo == null) || ($this->ve05_codigo == "") ){ 
       $this->erro_sql = " Campo ve05_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into veicmotoristas(
                                       ve05_codigo 
                                      ,ve05_numcgm 
                                      ,ve05_habilitacao 
                                      ,ve05_veiccadcategcnh 
                                      ,ve05_dtvenc 
                                      ,ve05_dtprimcnh 
                                      ,ve05_veiccadmotoristasit 
                       )
                values (
                                $this->ve05_codigo 
                               ,$this->ve05_numcgm 
                               ,'$this->ve05_habilitacao' 
                               ,$this->ve05_veiccadcategcnh 
                               ,".($this->ve05_dtvenc == "null" || $this->ve05_dtvenc == ""?"null":"'".$this->ve05_dtvenc."'")." 
                               ,".($this->ve05_dtprimcnh == "null" || $this->ve05_dtprimcnh == ""?"null":"'".$this->ve05_dtprimcnh."'")." 
                               ,$this->ve05_veiccadmotoristasit 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro de Motoristas ($this->ve05_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro de Motoristas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro de Motoristas ($this->ve05_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ve05_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ve05_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9269,'$this->ve05_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1593,9269,'','".AddSlashes(pg_result($resaco,0,'ve05_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1593,9270,'','".AddSlashes(pg_result($resaco,0,'ve05_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1593,9271,'','".AddSlashes(pg_result($resaco,0,'ve05_habilitacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1593,9272,'','".AddSlashes(pg_result($resaco,0,'ve05_veiccadcategcnh'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1593,9273,'','".AddSlashes(pg_result($resaco,0,'ve05_dtvenc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1593,9319,'','".AddSlashes(pg_result($resaco,0,'ve05_dtprimcnh'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1593,9320,'','".AddSlashes(pg_result($resaco,0,'ve05_veiccadmotoristasit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ve05_codigo=null) { 
      $this->atualizacampos();
     $sql = " update veicmotoristas set ";
     $virgula = "";
     if(trim($this->ve05_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve05_codigo"])){ 
       $sql  .= $virgula." ve05_codigo = $this->ve05_codigo ";
       $virgula = ",";
       if(trim($this->ve05_codigo) == null ){ 
         $this->erro_sql = " Campo Código do Motorista nao Informado.";
         $this->erro_campo = "ve05_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve05_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve05_numcgm"])){ 
       $sql  .= $virgula." ve05_numcgm = $this->ve05_numcgm ";
       $virgula = ",";
       if(trim($this->ve05_numcgm) == null ){ 
         $this->erro_sql = " Campo Motorista nao Informado.";
         $this->erro_campo = "ve05_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve05_habilitacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve05_habilitacao"])){ 
       $sql  .= $virgula." ve05_habilitacao = '$this->ve05_habilitacao' ";
       $virgula = ",";
       if(trim($this->ve05_habilitacao) == null ){ 
         $this->erro_sql = " Campo Nº Habilitação nao Informado.";
         $this->erro_campo = "ve05_habilitacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve05_veiccadcategcnh)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve05_veiccadcategcnh"])){ 
       $sql  .= $virgula." ve05_veiccadcategcnh = $this->ve05_veiccadcategcnh ";
       $virgula = ",";
       if(trim($this->ve05_veiccadcategcnh) == null ){ 
         $this->erro_sql = " Campo Categoria CNH nao Informado.";
         $this->erro_campo = "ve05_veiccadcategcnh";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve05_dtvenc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve05_dtvenc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ve05_dtvenc_dia"] !="") ){ 
       $sql  .= $virgula." ve05_dtvenc = '$this->ve05_dtvenc' ";
       $virgula = ",";
       if(trim($this->ve05_dtvenc) == null ){ 
         $this->erro_sql = " Campo Validade nao Informado.";
         $this->erro_campo = "ve05_dtvenc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ve05_dtvenc_dia"])){ 
         $sql  .= $virgula." ve05_dtvenc = null ";
         $virgula = ",";
         if(trim($this->ve05_dtvenc) == null ){ 
           $this->erro_sql = " Campo Validade nao Informado.";
           $this->erro_campo = "ve05_dtvenc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ve05_dtprimcnh)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve05_dtprimcnh_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ve05_dtprimcnh_dia"] !="") ){ 
       $sql  .= $virgula." ve05_dtprimcnh = '$this->ve05_dtprimcnh' ";
       $virgula = ",";
       if(trim($this->ve05_dtprimcnh) == null ){ 
         $this->erro_sql = " Campo Primeira Habilitação nao Informado.";
         $this->erro_campo = "ve05_dtprimcnh_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ve05_dtprimcnh_dia"])){ 
         $sql  .= $virgula." ve05_dtprimcnh = null ";
         $virgula = ",";
         if(trim($this->ve05_dtprimcnh) == null ){ 
           $this->erro_sql = " Campo Primeira Habilitação nao Informado.";
           $this->erro_campo = "ve05_dtprimcnh_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ve05_veiccadmotoristasit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve05_veiccadmotoristasit"])){ 
       $sql  .= $virgula." ve05_veiccadmotoristasit = $this->ve05_veiccadmotoristasit ";
       $virgula = ",";
       if(trim($this->ve05_veiccadmotoristasit) == null ){ 
         $this->erro_sql = " Campo Situação do Condutor nao Informado.";
         $this->erro_campo = "ve05_veiccadmotoristasit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ve05_codigo!=null){
       $sql .= " ve05_codigo = $this->ve05_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ve05_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9269,'$this->ve05_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve05_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1593,9269,'".AddSlashes(pg_result($resaco,$conresaco,'ve05_codigo'))."','$this->ve05_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve05_numcgm"]))
           $resac = db_query("insert into db_acount values($acount,1593,9270,'".AddSlashes(pg_result($resaco,$conresaco,'ve05_numcgm'))."','$this->ve05_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve05_habilitacao"]))
           $resac = db_query("insert into db_acount values($acount,1593,9271,'".AddSlashes(pg_result($resaco,$conresaco,'ve05_habilitacao'))."','$this->ve05_habilitacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve05_veiccadcategcnh"]))
           $resac = db_query("insert into db_acount values($acount,1593,9272,'".AddSlashes(pg_result($resaco,$conresaco,'ve05_veiccadcategcnh'))."','$this->ve05_veiccadcategcnh',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve05_dtvenc"]))
           $resac = db_query("insert into db_acount values($acount,1593,9273,'".AddSlashes(pg_result($resaco,$conresaco,'ve05_dtvenc'))."','$this->ve05_dtvenc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve05_dtprimcnh"]))
           $resac = db_query("insert into db_acount values($acount,1593,9319,'".AddSlashes(pg_result($resaco,$conresaco,'ve05_dtprimcnh'))."','$this->ve05_dtprimcnh',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve05_veiccadmotoristasit"]))
           $resac = db_query("insert into db_acount values($acount,1593,9320,'".AddSlashes(pg_result($resaco,$conresaco,'ve05_veiccadmotoristasit'))."','$this->ve05_veiccadmotoristasit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Motoristas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ve05_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Motoristas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ve05_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ve05_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ve05_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ve05_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9269,'$ve05_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1593,9269,'','".AddSlashes(pg_result($resaco,$iresaco,'ve05_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1593,9270,'','".AddSlashes(pg_result($resaco,$iresaco,'ve05_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1593,9271,'','".AddSlashes(pg_result($resaco,$iresaco,'ve05_habilitacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1593,9272,'','".AddSlashes(pg_result($resaco,$iresaco,'ve05_veiccadcategcnh'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1593,9273,'','".AddSlashes(pg_result($resaco,$iresaco,'ve05_dtvenc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1593,9319,'','".AddSlashes(pg_result($resaco,$iresaco,'ve05_dtprimcnh'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1593,9320,'','".AddSlashes(pg_result($resaco,$iresaco,'ve05_veiccadmotoristasit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from veicmotoristas
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ve05_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ve05_codigo = $ve05_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Motoristas nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ve05_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Motoristas nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ve05_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ve05_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:veicmotoristas";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $ve05_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from veicmotoristas ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = veicmotoristas.ve05_numcgm";
     $sql .= "      inner join veiccadcategcnh  on  veiccadcategcnh.ve30_codigo = veicmotoristas.ve05_veiccadcategcnh";
     $sql .= "      inner join veiccadmotoristasit  on  veiccadmotoristasit.ve33_codigo = veicmotoristas.ve05_veiccadmotoristasit";
     $sql2 = "";
     if($dbwhere==""){
       if($ve05_codigo!=null ){
         $sql2 .= " where veicmotoristas.ve05_codigo = $ve05_codigo "; 
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
   function sql_query_central ($ve05_codigo=null,$campos="*",$ordem=null,$dbwhere="",$dbinner=""){ 
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
     $sql .= " from veicmotoristas ";
     $sql .= "      inner join cgm                   on  cgm.z01_numcgm = veicmotoristas.ve05_numcgm";
     $sql .= "      inner join veiccadcategcnh       on veiccadcategcnh.ve30_codigo = veicmotoristas.ve05_veiccadcategcnh";
     $sql .= "      inner join veiccadmotoristasit   on veiccadmotoristasit.ve33_codigo = veicmotoristas.ve05_veiccadmotoristasit";
     $sql .= "      inner join veicmotoristascentral on veicmotoristascentral.ve41_veicmotoristas = veicmotoristas.ve05_codigo";
     $sql .= "      inner join veiccadcentral        on veiccadcentral.ve36_sequencial = veicmotoristascentral.ve41_veiccadcentral";
     $sql2 = "";
     
     if ($dbinner != ""){
          $sql .= $dbinner;
     }

     if($dbwhere==""){
       if($ve05_codigo!=null ){
         $sql2 .= " where veicmotoristas.ve05_codigo = $ve05_codigo "; 
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
   function sql_query_file ( $ve05_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from veicmotoristas ";
     $sql2 = "";
     if($dbwhere==""){
       if($ve05_codigo!=null ){
         $sql2 .= " where veicmotoristas.ve05_codigo = $ve05_codigo "; 
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
   function sql_query_veic ($ve05_codigo=null,$campos="*",$ordem=null,$dbwhere="",$dbinner=""){ 
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
     $sql .= " from veicmotoristas ";
     $sql .= "      inner join cgm                   on  cgm.z01_numcgm = veicmotoristas.ve05_numcgm";
     $sql .= "      inner join veiccadcategcnh       on veiccadcategcnh.ve30_codigo = veicmotoristas.ve05_veiccadcategcnh";
     $sql .= "      inner join veiccadmotoristasit   on veiccadmotoristasit.ve33_codigo = veicmotoristas.ve05_veiccadmotoristasit";
     $sql .= "      left  join veicmotoristascentral on veicmotoristascentral.ve41_veicmotoristas = veicmotoristas.ve05_codigo";
     $sql .= "      inner join veiccadcentral        on veiccadcentral.ve36_sequencial = veicmotoristascentral.ve41_veiccadcentral";
     $sql .= "      left  join veiccadcentraldepart  on veiccadcentraldepart.ve37_veiccadcentral = veiccadcentral.ve36_sequencial";
     $sql .= "      left  join  rhpessoal on  cgm.z01_numcgm = rhpessoal.rh01_numcgm ";
     $sql2 = "";
     
     if ($dbinner != ""){
          $sql .= $dbinner;
     }

     if($dbwhere==""){
       if($ve05_codigo!=null ){
         $sql2 .= " where veicmotoristas.ve05_codigo = $ve05_codigo "; 
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