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
//CLASSE DA ENTIDADE veicparam
class cl_veicparam { 
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
   var $ve50_codigo = 0; 
   var $ve50_instit = 0; 
   var $ve50_veiccadtipo = 0; 
   var $ve50_veiccadcategcnh = 0; 
   var $ve50_integrapatri = 0; 
   var $ve50_postoproprio = 0; 
   var $ve50_integrapessoal = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ve50_codigo = int4 = Código Sequencial 
                 ve50_instit = int4 = Instituição 
                 ve50_veiccadtipo = int4 = Tipo Padrão 
                 ve50_veiccadcategcnh = int4 = Categoria CNH Exigida 
                 ve50_integrapatri = int4 = Integrado com Patrimonio 
                 ve50_postoproprio = int4 = Abastecimento Posto Proprio 
                 ve50_integrapessoal = int4 = Integração com módulo Pessoal 
                 ";
   //funcao construtor da classe 
   function cl_veicparam() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("veicparam"); 
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
       $this->ve50_codigo = ($this->ve50_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ve50_codigo"]:$this->ve50_codigo);
       $this->ve50_instit = ($this->ve50_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["ve50_instit"]:$this->ve50_instit);
       $this->ve50_veiccadtipo = ($this->ve50_veiccadtipo == ""?@$GLOBALS["HTTP_POST_VARS"]["ve50_veiccadtipo"]:$this->ve50_veiccadtipo);
       $this->ve50_veiccadcategcnh = ($this->ve50_veiccadcategcnh == ""?@$GLOBALS["HTTP_POST_VARS"]["ve50_veiccadcategcnh"]:$this->ve50_veiccadcategcnh);
       $this->ve50_integrapatri = ($this->ve50_integrapatri == ""?@$GLOBALS["HTTP_POST_VARS"]["ve50_integrapatri"]:$this->ve50_integrapatri);
       $this->ve50_postoproprio = ($this->ve50_postoproprio == ""?@$GLOBALS["HTTP_POST_VARS"]["ve50_postoproprio"]:$this->ve50_postoproprio);
       $this->ve50_integrapessoal = ($this->ve50_integrapessoal == ""?@$GLOBALS["HTTP_POST_VARS"]["ve50_integrapessoal"]:$this->ve50_integrapessoal);
     }else{
       $this->ve50_codigo = ($this->ve50_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ve50_codigo"]:$this->ve50_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ve50_codigo){ 
      $this->atualizacampos();
     if($this->ve50_instit == null ){ 
       $this->erro_sql = " Campo Instituição nao Informado.";
       $this->erro_campo = "ve50_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve50_veiccadtipo == null ){ 
       $this->erro_sql = " Campo Tipo Padrão nao Informado.";
       $this->erro_campo = "ve50_veiccadtipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve50_veiccadcategcnh == null ){ 
       $this->erro_sql = " Campo Categoria CNH Exigida nao Informado.";
       $this->erro_campo = "ve50_veiccadcategcnh";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve50_integrapatri == null ){ 
       $this->erro_sql = " Campo Integrado com Patrimonio nao Informado.";
       $this->erro_campo = "ve50_integrapatri";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve50_postoproprio == null ){ 
       $this->erro_sql = " Campo Abastecimento Posto Proprio nao Informado.";
       $this->erro_campo = "ve50_postoproprio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve50_integrapessoal == null ){ 
       $this->erro_sql = " Campo Integração com módulo Pessoal nao Informado.";
       $this->erro_campo = "ve50_integrapessoal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ve50_codigo == "" || $ve50_codigo == null ){
       $result = db_query("select nextval('veicparam_ve50_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: veicparam_ve50_codigo_seq do campo: ve50_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ve50_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from veicparam_ve50_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ve50_codigo)){
         $this->erro_sql = " Campo ve50_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ve50_codigo = $ve50_codigo; 
       }
     }
     if(($this->ve50_codigo == null) || ($this->ve50_codigo == "") ){ 
       $this->erro_sql = " Campo ve50_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into veicparam(
                                       ve50_codigo 
                                      ,ve50_instit 
                                      ,ve50_veiccadtipo 
                                      ,ve50_veiccadcategcnh 
                                      ,ve50_integrapatri 
                                      ,ve50_postoproprio 
                                      ,ve50_integrapessoal 
                       )
                values (
                                $this->ve50_codigo 
                               ,$this->ve50_instit 
                               ,$this->ve50_veiccadtipo 
                               ,$this->ve50_veiccadcategcnh 
                               ,$this->ve50_integrapatri 
                               ,$this->ve50_postoproprio 
                               ,$this->ve50_integrapessoal 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Parâmetros do modulo veículos  ($this->ve50_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Parâmetros do modulo veículos  já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Parâmetros do modulo veículos  ($this->ve50_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ve50_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ve50_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9241,'$this->ve50_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1588,9241,'','".AddSlashes(pg_result($resaco,0,'ve50_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1588,9242,'','".AddSlashes(pg_result($resaco,0,'ve50_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1588,9243,'','".AddSlashes(pg_result($resaco,0,'ve50_veiccadtipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1588,9245,'','".AddSlashes(pg_result($resaco,0,'ve50_veiccadcategcnh'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1588,9246,'','".AddSlashes(pg_result($resaco,0,'ve50_integrapatri'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1588,9247,'','".AddSlashes(pg_result($resaco,0,'ve50_postoproprio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1588,10551,'','".AddSlashes(pg_result($resaco,0,'ve50_integrapessoal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ve50_codigo=null) { 
      $this->atualizacampos();
     $sql = " update veicparam set ";
     $virgula = "";
     if(trim($this->ve50_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve50_codigo"])){ 
       $sql  .= $virgula." ve50_codigo = $this->ve50_codigo ";
       $virgula = ",";
       if(trim($this->ve50_codigo) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "ve50_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve50_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve50_instit"])){ 
       $sql  .= $virgula." ve50_instit = $this->ve50_instit ";
       $virgula = ",";
       if(trim($this->ve50_instit) == null ){ 
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "ve50_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve50_veiccadtipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve50_veiccadtipo"])){ 
       $sql  .= $virgula." ve50_veiccadtipo = $this->ve50_veiccadtipo ";
       $virgula = ",";
       if(trim($this->ve50_veiccadtipo) == null ){ 
         $this->erro_sql = " Campo Tipo Padrão nao Informado.";
         $this->erro_campo = "ve50_veiccadtipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve50_veiccadcategcnh)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve50_veiccadcategcnh"])){ 
       $sql  .= $virgula." ve50_veiccadcategcnh = $this->ve50_veiccadcategcnh ";
       $virgula = ",";
       if(trim($this->ve50_veiccadcategcnh) == null ){ 
         $this->erro_sql = " Campo Categoria CNH Exigida nao Informado.";
         $this->erro_campo = "ve50_veiccadcategcnh";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve50_integrapatri)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve50_integrapatri"])){ 
       $sql  .= $virgula." ve50_integrapatri = $this->ve50_integrapatri ";
       $virgula = ",";
       if(trim($this->ve50_integrapatri) == null ){ 
         $this->erro_sql = " Campo Integrado com Patrimonio nao Informado.";
         $this->erro_campo = "ve50_integrapatri";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve50_postoproprio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve50_postoproprio"])){ 
       $sql  .= $virgula." ve50_postoproprio = $this->ve50_postoproprio ";
       $virgula = ",";
       if(trim($this->ve50_postoproprio) == null ){ 
         $this->erro_sql = " Campo Abastecimento Posto Proprio nao Informado.";
         $this->erro_campo = "ve50_postoproprio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve50_integrapessoal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve50_integrapessoal"])){ 
       $sql  .= $virgula." ve50_integrapessoal = $this->ve50_integrapessoal ";
       $virgula = ",";
       if(trim($this->ve50_integrapessoal) == null ){ 
         $this->erro_sql = " Campo Integração com módulo Pessoal nao Informado.";
         $this->erro_campo = "ve50_integrapessoal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ve50_codigo!=null){
       $sql .= " ve50_codigo = $this->ve50_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ve50_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9241,'$this->ve50_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve50_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1588,9241,'".AddSlashes(pg_result($resaco,$conresaco,'ve50_codigo'))."','$this->ve50_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve50_instit"]))
           $resac = db_query("insert into db_acount values($acount,1588,9242,'".AddSlashes(pg_result($resaco,$conresaco,'ve50_instit'))."','$this->ve50_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve50_veiccadtipo"]))
           $resac = db_query("insert into db_acount values($acount,1588,9243,'".AddSlashes(pg_result($resaco,$conresaco,'ve50_veiccadtipo'))."','$this->ve50_veiccadtipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve50_veiccadcategcnh"]))
           $resac = db_query("insert into db_acount values($acount,1588,9245,'".AddSlashes(pg_result($resaco,$conresaco,'ve50_veiccadcategcnh'))."','$this->ve50_veiccadcategcnh',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve50_integrapatri"]))
           $resac = db_query("insert into db_acount values($acount,1588,9246,'".AddSlashes(pg_result($resaco,$conresaco,'ve50_integrapatri'))."','$this->ve50_integrapatri',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve50_postoproprio"]))
           $resac = db_query("insert into db_acount values($acount,1588,9247,'".AddSlashes(pg_result($resaco,$conresaco,'ve50_postoproprio'))."','$this->ve50_postoproprio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve50_integrapessoal"]))
           $resac = db_query("insert into db_acount values($acount,1588,10551,'".AddSlashes(pg_result($resaco,$conresaco,'ve50_integrapessoal'))."','$this->ve50_integrapessoal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Parâmetros do modulo veículos  nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ve50_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Parâmetros do modulo veículos  nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ve50_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ve50_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ve50_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ve50_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9241,'$ve50_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1588,9241,'','".AddSlashes(pg_result($resaco,$iresaco,'ve50_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1588,9242,'','".AddSlashes(pg_result($resaco,$iresaco,'ve50_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1588,9243,'','".AddSlashes(pg_result($resaco,$iresaco,'ve50_veiccadtipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1588,9245,'','".AddSlashes(pg_result($resaco,$iresaco,'ve50_veiccadcategcnh'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1588,9246,'','".AddSlashes(pg_result($resaco,$iresaco,'ve50_integrapatri'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1588,9247,'','".AddSlashes(pg_result($resaco,$iresaco,'ve50_postoproprio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1588,10551,'','".AddSlashes(pg_result($resaco,$iresaco,'ve50_integrapessoal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from veicparam
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ve50_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ve50_codigo = $ve50_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Parâmetros do modulo veículos  nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ve50_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Parâmetros do modulo veículos  nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ve50_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ve50_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:veicparam";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $ve50_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from veicparam ";
     $sql .= "      inner join db_config  on  db_config.codigo = veicparam.ve50_instit";
     $sql .= "      inner join veiccadtipo  on  veiccadtipo.ve20_codigo = veicparam.ve50_veiccadtipo";
     $sql .= "      inner join veiccadcategcnh  on  veiccadcategcnh.ve30_codigo = veicparam.ve50_veiccadcategcnh";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($ve50_codigo!=null ){
         $sql2 .= " where veicparam.ve50_codigo = $ve50_codigo "; 
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
   function sql_query_file ( $ve50_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from veicparam ";
     $sql2 = "";
     if($dbwhere==""){
       if($ve50_codigo!=null ){
         $sql2 .= " where veicparam.ve50_codigo = $ve50_codigo "; 
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