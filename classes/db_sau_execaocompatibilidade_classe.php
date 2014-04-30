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
//CLASSE DA ENTIDADE sau_execaocompatibilidade
class cl_sau_execaocompatibilidade { 
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
   var $sd67_i_codigo = 0; 
   var $sd67_i_procrestricao = 0; 
   var $sd67_i_procprincipal = 0; 
   var $sd67_i_regprincipal = 0; 
   var $sd67_i_proccompativel = 0; 
   var $sd67_i_regcompativel = 0; 
   var $sd67_i_compatibilidade = 0; 
   var $sd67_i_anocomp = 0; 
   var $sd67_i_mescomp = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 sd67_i_codigo = int8 = Código 
                 sd67_i_procrestricao = int8 = Restrição 
                 sd67_i_procprincipal = int8 = Principal 
                 sd67_i_regprincipal = int8 = Registro Principal 
                 sd67_i_proccompativel = int8 = Compativel 
                 sd67_i_regcompativel = int8 = Registro Compativel 
                 sd67_i_compatibilidade = int8 = Compatibilidade 
                 sd67_i_anocomp = int4 = Ano 
                 sd67_i_mescomp = int4 = Mes 
                 ";
   //funcao construtor da classe 
   function cl_sau_execaocompatibilidade() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("sau_execaocompatibilidade"); 
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
       $this->sd67_i_codigo = ($this->sd67_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd67_i_codigo"]:$this->sd67_i_codigo);
       $this->sd67_i_procrestricao = ($this->sd67_i_procrestricao == ""?@$GLOBALS["HTTP_POST_VARS"]["sd67_i_procrestricao"]:$this->sd67_i_procrestricao);
       $this->sd67_i_procprincipal = ($this->sd67_i_procprincipal == ""?@$GLOBALS["HTTP_POST_VARS"]["sd67_i_procprincipal"]:$this->sd67_i_procprincipal);
       $this->sd67_i_regprincipal = ($this->sd67_i_regprincipal == ""?@$GLOBALS["HTTP_POST_VARS"]["sd67_i_regprincipal"]:$this->sd67_i_regprincipal);
       $this->sd67_i_proccompativel = ($this->sd67_i_proccompativel == ""?@$GLOBALS["HTTP_POST_VARS"]["sd67_i_proccompativel"]:$this->sd67_i_proccompativel);
       $this->sd67_i_regcompativel = ($this->sd67_i_regcompativel == ""?@$GLOBALS["HTTP_POST_VARS"]["sd67_i_regcompativel"]:$this->sd67_i_regcompativel);
       $this->sd67_i_compatibilidade = ($this->sd67_i_compatibilidade == ""?@$GLOBALS["HTTP_POST_VARS"]["sd67_i_compatibilidade"]:$this->sd67_i_compatibilidade);
       $this->sd67_i_anocomp = ($this->sd67_i_anocomp == ""?@$GLOBALS["HTTP_POST_VARS"]["sd67_i_anocomp"]:$this->sd67_i_anocomp);
       $this->sd67_i_mescomp = ($this->sd67_i_mescomp == ""?@$GLOBALS["HTTP_POST_VARS"]["sd67_i_mescomp"]:$this->sd67_i_mescomp);
     }else{
       $this->sd67_i_codigo = ($this->sd67_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd67_i_codigo"]:$this->sd67_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($sd67_i_codigo){ 
      $this->atualizacampos();
     if($this->sd67_i_procrestricao == null ){ 
       $this->erro_sql = " Campo Restrição nao Informado.";
       $this->erro_campo = "sd67_i_procrestricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd67_i_procprincipal == null ){ 
       $this->erro_sql = " Campo Principal nao Informado.";
       $this->erro_campo = "sd67_i_procprincipal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd67_i_regprincipal == null ){ 
       $this->erro_sql = " Campo Registro Principal nao Informado.";
       $this->erro_campo = "sd67_i_regprincipal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd67_i_proccompativel == null ){ 
       $this->erro_sql = " Campo Compativel nao Informado.";
       $this->erro_campo = "sd67_i_proccompativel";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd67_i_regcompativel == null ){ 
       $this->erro_sql = " Campo Registro Compativel nao Informado.";
       $this->erro_campo = "sd67_i_regcompativel";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd67_i_compatibilidade == null ){ 
       $this->erro_sql = " Campo Compatibilidade nao Informado.";
       $this->erro_campo = "sd67_i_compatibilidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd67_i_anocomp == null ){ 
       $this->erro_sql = " Campo Ano nao Informado.";
       $this->erro_campo = "sd67_i_anocomp";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd67_i_mescomp == null ){ 
       $this->erro_sql = " Campo Mes nao Informado.";
       $this->erro_campo = "sd67_i_mescomp";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($sd67_i_codigo == "" || $sd67_i_codigo == null ){
       $result = db_query("select nextval('sau_execaocompatibilidade_sd67_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: sau_execaocompatibilidade_sd67_i_codigo_seq do campo: sd67_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->sd67_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from sau_execaocompatibilidade_sd67_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $sd67_i_codigo)){
         $this->erro_sql = " Campo sd67_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->sd67_i_codigo = $sd67_i_codigo; 
       }
     }
     if(($this->sd67_i_codigo == null) || ($this->sd67_i_codigo == "") ){ 
       $this->erro_sql = " Campo sd67_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into sau_execaocompatibilidade(
                                       sd67_i_codigo 
                                      ,sd67_i_procrestricao 
                                      ,sd67_i_procprincipal 
                                      ,sd67_i_regprincipal 
                                      ,sd67_i_proccompativel 
                                      ,sd67_i_regcompativel 
                                      ,sd67_i_compatibilidade 
                                      ,sd67_i_anocomp 
                                      ,sd67_i_mescomp 
                       )
                values (
                                $this->sd67_i_codigo 
                               ,$this->sd67_i_procrestricao 
                               ,$this->sd67_i_procprincipal 
                               ,$this->sd67_i_regprincipal 
                               ,$this->sd67_i_proccompativel 
                               ,$this->sd67_i_regcompativel 
                               ,$this->sd67_i_compatibilidade 
                               ,$this->sd67_i_anocomp 
                               ,$this->sd67_i_mescomp 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Exeção da Compatibilidade ($this->sd67_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Exeção da Compatibilidade já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Exeção da Compatibilidade ($this->sd67_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd67_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->sd67_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,11498,'$this->sd67_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2006,11498,'','".AddSlashes(pg_result($resaco,0,'sd67_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2006,11499,'','".AddSlashes(pg_result($resaco,0,'sd67_i_procrestricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2006,11500,'','".AddSlashes(pg_result($resaco,0,'sd67_i_procprincipal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2006,11501,'','".AddSlashes(pg_result($resaco,0,'sd67_i_regprincipal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2006,11502,'','".AddSlashes(pg_result($resaco,0,'sd67_i_proccompativel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2006,11503,'','".AddSlashes(pg_result($resaco,0,'sd67_i_regcompativel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2006,11504,'','".AddSlashes(pg_result($resaco,0,'sd67_i_compatibilidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2006,11666,'','".AddSlashes(pg_result($resaco,0,'sd67_i_anocomp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2006,11667,'','".AddSlashes(pg_result($resaco,0,'sd67_i_mescomp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($sd67_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update sau_execaocompatibilidade set ";
     $virgula = "";
     if(trim($this->sd67_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd67_i_codigo"])){ 
       $sql  .= $virgula." sd67_i_codigo = $this->sd67_i_codigo ";
       $virgula = ",";
       if(trim($this->sd67_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "sd67_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd67_i_procrestricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd67_i_procrestricao"])){ 
       $sql  .= $virgula." sd67_i_procrestricao = $this->sd67_i_procrestricao ";
       $virgula = ",";
       if(trim($this->sd67_i_procrestricao) == null ){ 
         $this->erro_sql = " Campo Restrição nao Informado.";
         $this->erro_campo = "sd67_i_procrestricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd67_i_procprincipal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd67_i_procprincipal"])){ 
       $sql  .= $virgula." sd67_i_procprincipal = $this->sd67_i_procprincipal ";
       $virgula = ",";
       if(trim($this->sd67_i_procprincipal) == null ){ 
         $this->erro_sql = " Campo Principal nao Informado.";
         $this->erro_campo = "sd67_i_procprincipal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd67_i_regprincipal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd67_i_regprincipal"])){ 
       $sql  .= $virgula." sd67_i_regprincipal = $this->sd67_i_regprincipal ";
       $virgula = ",";
       if(trim($this->sd67_i_regprincipal) == null ){ 
         $this->erro_sql = " Campo Registro Principal nao Informado.";
         $this->erro_campo = "sd67_i_regprincipal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd67_i_proccompativel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd67_i_proccompativel"])){ 
       $sql  .= $virgula." sd67_i_proccompativel = $this->sd67_i_proccompativel ";
       $virgula = ",";
       if(trim($this->sd67_i_proccompativel) == null ){ 
         $this->erro_sql = " Campo Compativel nao Informado.";
         $this->erro_campo = "sd67_i_proccompativel";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd67_i_regcompativel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd67_i_regcompativel"])){ 
       $sql  .= $virgula." sd67_i_regcompativel = $this->sd67_i_regcompativel ";
       $virgula = ",";
       if(trim($this->sd67_i_regcompativel) == null ){ 
         $this->erro_sql = " Campo Registro Compativel nao Informado.";
         $this->erro_campo = "sd67_i_regcompativel";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd67_i_compatibilidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd67_i_compatibilidade"])){ 
       $sql  .= $virgula." sd67_i_compatibilidade = $this->sd67_i_compatibilidade ";
       $virgula = ",";
       if(trim($this->sd67_i_compatibilidade) == null ){ 
         $this->erro_sql = " Campo Compatibilidade nao Informado.";
         $this->erro_campo = "sd67_i_compatibilidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd67_i_anocomp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd67_i_anocomp"])){ 
       $sql  .= $virgula." sd67_i_anocomp = $this->sd67_i_anocomp ";
       $virgula = ",";
       if(trim($this->sd67_i_anocomp) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "sd67_i_anocomp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd67_i_mescomp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd67_i_mescomp"])){ 
       $sql  .= $virgula." sd67_i_mescomp = $this->sd67_i_mescomp ";
       $virgula = ",";
       if(trim($this->sd67_i_mescomp) == null ){ 
         $this->erro_sql = " Campo Mes nao Informado.";
         $this->erro_campo = "sd67_i_mescomp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($sd67_i_codigo!=null){
       $sql .= " sd67_i_codigo = $this->sd67_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->sd67_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11498,'$this->sd67_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd67_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,2006,11498,'".AddSlashes(pg_result($resaco,$conresaco,'sd67_i_codigo'))."','$this->sd67_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd67_i_procrestricao"]))
           $resac = db_query("insert into db_acount values($acount,2006,11499,'".AddSlashes(pg_result($resaco,$conresaco,'sd67_i_procrestricao'))."','$this->sd67_i_procrestricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd67_i_procprincipal"]))
           $resac = db_query("insert into db_acount values($acount,2006,11500,'".AddSlashes(pg_result($resaco,$conresaco,'sd67_i_procprincipal'))."','$this->sd67_i_procprincipal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd67_i_regprincipal"]))
           $resac = db_query("insert into db_acount values($acount,2006,11501,'".AddSlashes(pg_result($resaco,$conresaco,'sd67_i_regprincipal'))."','$this->sd67_i_regprincipal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd67_i_proccompativel"]))
           $resac = db_query("insert into db_acount values($acount,2006,11502,'".AddSlashes(pg_result($resaco,$conresaco,'sd67_i_proccompativel'))."','$this->sd67_i_proccompativel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd67_i_regcompativel"]))
           $resac = db_query("insert into db_acount values($acount,2006,11503,'".AddSlashes(pg_result($resaco,$conresaco,'sd67_i_regcompativel'))."','$this->sd67_i_regcompativel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd67_i_compatibilidade"]))
           $resac = db_query("insert into db_acount values($acount,2006,11504,'".AddSlashes(pg_result($resaco,$conresaco,'sd67_i_compatibilidade'))."','$this->sd67_i_compatibilidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd67_i_anocomp"]))
           $resac = db_query("insert into db_acount values($acount,2006,11666,'".AddSlashes(pg_result($resaco,$conresaco,'sd67_i_anocomp'))."','$this->sd67_i_anocomp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd67_i_mescomp"]))
           $resac = db_query("insert into db_acount values($acount,2006,11667,'".AddSlashes(pg_result($resaco,$conresaco,'sd67_i_mescomp'))."','$this->sd67_i_mescomp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Exeção da Compatibilidade nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd67_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Exeção da Compatibilidade nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd67_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd67_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($sd67_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($sd67_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11498,'$sd67_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2006,11498,'','".AddSlashes(pg_result($resaco,$iresaco,'sd67_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2006,11499,'','".AddSlashes(pg_result($resaco,$iresaco,'sd67_i_procrestricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2006,11500,'','".AddSlashes(pg_result($resaco,$iresaco,'sd67_i_procprincipal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2006,11501,'','".AddSlashes(pg_result($resaco,$iresaco,'sd67_i_regprincipal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2006,11502,'','".AddSlashes(pg_result($resaco,$iresaco,'sd67_i_proccompativel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2006,11503,'','".AddSlashes(pg_result($resaco,$iresaco,'sd67_i_regcompativel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2006,11504,'','".AddSlashes(pg_result($resaco,$iresaco,'sd67_i_compatibilidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2006,11666,'','".AddSlashes(pg_result($resaco,$iresaco,'sd67_i_anocomp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2006,11667,'','".AddSlashes(pg_result($resaco,$iresaco,'sd67_i_mescomp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from sau_execaocompatibilidade
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($sd67_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " sd67_i_codigo = $sd67_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Exeção da Compatibilidade nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$sd67_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Exeção da Compatibilidade nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$sd67_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$sd67_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:sau_execaocompatibilidade";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $sd67_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from sau_execaocompatibilidade ";
     $sql .= "     inner join sau_tipocompatibilidade on sau_tipocompatibilidade.sd68_i_codigo = sau_execaocompatibilidade.sd67_i_compatibilidade";
     $sql .= "     inner join sau_procedimento    as a on a.sd63_i_codigo                       = sau_execaocompatibilidade.sd67_i_procrestricao ";
     $sql .= "     inner join sau_procedimento    as b on b.sd63_i_codigo                       = sau_execaocompatibilidade.sd67_i_procprincipal ";
     $sql .= "     inner join sau_procedimento    as c on c.sd63_i_codigo                       = sau_execaocompatibilidade.sd67_i_proccompativel";
     $sql .= "     inner join sau_registro        as d on d.sd84_i_codigo                       = sau_execaocompatibilidade.sd67_i_regprincipal  ";
     $sql .= "     inner join sau_registro        as e on e.sd84_i_codigo                       = sau_execaocompatibilidade.sd67_i_regcompativel ";
     $sql .= "     inner join sau_financiamento   as f on f.sd65_i_codigo                       = a.sd63_i_financiamento                         ";
     $sql .= "     inner join sau_financiamento   as g on g.sd65_i_codigo                       = b.sd63_i_financiamento                         ";
     $sql .= "     inner join sau_financiamento   as h on h.sd65_i_codigo                       = c.sd63_i_financiamento                         ";
     $sql .= "     inner join sau_complexidade    as i on i.sd69_i_codigo                       = a.sd63_i_complexidade                          ";
     $sql .= "     inner join sau_complexidade    as j on j.sd69_i_codigo                       = b.sd63_i_complexidade                          ";
     $sql .= "     inner join sau_complexidade    as l on l.sd69_i_codigo                       = c.sd63_i_complexidade                          ";
     $sql .= "     left join sau_rubrica         as m on m.sd64_i_codigo                       = a.sd63_i_rubrica                               ";
     $sql .= "     left join sau_rubrica         as n on n.sd64_i_codigo                       = b.sd63_i_rubrica                               ";
     $sql .= "     left join sau_rubrica         as o on o.sd64_i_codigo                       = c.sd63_i_rubrica                               ";
     $sql2 = "";
     if($dbwhere==""){
       if($sd67_i_codigo!=null ){
         $sql2 .= " where sau_execaocompatibilidade.sd67_i_codigo = $sd67_i_codigo ";
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
   function sql_query_file ( $sd67_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from sau_execaocompatibilidade ";
     $sql2 = "";
     if($dbwhere==""){
       if($sd67_i_codigo!=null ){
         $sql2 .= " where sau_execaocompatibilidade.sd67_i_codigo = $sd67_i_codigo ";
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