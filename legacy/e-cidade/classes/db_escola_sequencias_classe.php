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

//MODULO: educação
//CLASSE DA ENTIDADE escola_sequencias
class cl_escola_sequencias { 
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
   var $ed129_i_codigo = 0; 
   var $ed129_i_escola = 0; 
   var $ed129_i_inicio = 0; 
   var $ed129_i_final = 0; 
   var $ed129_i_ultatualizse = 0; 
   var $ed129_i_ultatualizes = 0; 
   var $ed129_i_numinicio = 0; 
   var $ed129_i_numfinal = 0; 
   var $ed129_c_ativo = null; 
   var $ed129_c_ulttransacao = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed129_i_codigo = int8 = Código 
                 ed129_i_escola = int8 = Escola 
                 ed129_i_inicio = int8 = Início da Sequência 
                 ed129_i_final = int8 = Final da Sequência 
                 ed129_i_ultatualizse = int8 = Última Atualização 
                 ed129_i_ultatualizes = int8 = Última Atualização 
                 ed129_i_numinicio = int8 = Início da Sequência 
                 ed129_i_numfinal = int8 = Final da Sequência 
                 ed129_c_ativo = char(1) = Ativo 
                 ed129_c_ulttransacao = char(2) = Última Transação 
                 ";
   //funcao construtor da classe 
   function cl_escola_sequencias() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("escola_sequencias"); 
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
       $this->ed129_i_codigo = ($this->ed129_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed129_i_codigo"]:$this->ed129_i_codigo);
       $this->ed129_i_escola = ($this->ed129_i_escola == ""?@$GLOBALS["HTTP_POST_VARS"]["ed129_i_escola"]:$this->ed129_i_escola);
       $this->ed129_i_inicio = ($this->ed129_i_inicio == ""?@$GLOBALS["HTTP_POST_VARS"]["ed129_i_inicio"]:$this->ed129_i_inicio);
       $this->ed129_i_final = ($this->ed129_i_final == ""?@$GLOBALS["HTTP_POST_VARS"]["ed129_i_final"]:$this->ed129_i_final);
       $this->ed129_i_ultatualizse = ($this->ed129_i_ultatualizse == ""?@$GLOBALS["HTTP_POST_VARS"]["ed129_i_ultatualizse"]:$this->ed129_i_ultatualizse);
       $this->ed129_i_ultatualizes = ($this->ed129_i_ultatualizes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed129_i_ultatualizes"]:$this->ed129_i_ultatualizes);
       $this->ed129_i_numinicio = ($this->ed129_i_numinicio == ""?@$GLOBALS["HTTP_POST_VARS"]["ed129_i_numinicio"]:$this->ed129_i_numinicio);
       $this->ed129_i_numfinal = ($this->ed129_i_numfinal == ""?@$GLOBALS["HTTP_POST_VARS"]["ed129_i_numfinal"]:$this->ed129_i_numfinal);
       $this->ed129_c_ativo = ($this->ed129_c_ativo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed129_c_ativo"]:$this->ed129_c_ativo);
       $this->ed129_c_ulttransacao = ($this->ed129_c_ulttransacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed129_c_ulttransacao"]:$this->ed129_c_ulttransacao);
     }else{
       $this->ed129_i_codigo = ($this->ed129_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed129_i_codigo"]:$this->ed129_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed129_i_codigo){ 
      $this->atualizacampos();
     if($this->ed129_i_escola == null ){ 
       $this->erro_sql = " Campo Escola nao Informado.";
       $this->erro_campo = "ed129_i_escola";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed129_i_inicio == null ){ 
       $this->erro_sql = " Campo Início da Sequência nao Informado.";
       $this->erro_campo = "ed129_i_inicio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed129_i_final == null ){ 
       $this->erro_sql = " Campo Final da Sequência nao Informado.";
       $this->erro_campo = "ed129_i_final";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed129_i_ultatualizse == null ){ 
       $this->erro_sql = " Campo Última Atualização nao Informado.";
       $this->erro_campo = "ed129_i_ultatualizse";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed129_i_ultatualizes == null ){ 
       $this->erro_sql = " Campo Última Atualização nao Informado.";
       $this->erro_campo = "ed129_i_ultatualizes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed129_i_numinicio == null ){ 
       $this->erro_sql = " Campo Início da Sequência nao Informado.";
       $this->erro_campo = "ed129_i_numinicio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed129_i_numfinal == null ){ 
       $this->erro_sql = " Campo Final da Sequência nao Informado.";
       $this->erro_campo = "ed129_i_numfinal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed129_c_ativo == null ){ 
       $this->erro_sql = " Campo Ativo nao Informado.";
       $this->erro_campo = "ed129_c_ativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed129_c_ulttransacao == null ){ 
       $this->erro_sql = " Campo Última Transação nao Informado.";
       $this->erro_campo = "ed129_c_ulttransacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed129_i_codigo == "" || $ed129_i_codigo == null ){
       $result = db_query("select nextval('escola_sequencias_ed129_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: escola_sequencias_ed129_i_codigo_seq do campo: ed129_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed129_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from escola_sequencias_ed129_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed129_i_codigo)){
         $this->erro_sql = " Campo ed129_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed129_i_codigo = $ed129_i_codigo; 
       }
     }
     if(($this->ed129_i_codigo == null) || ($this->ed129_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed129_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into escola_sequencias(
                                       ed129_i_codigo 
                                      ,ed129_i_escola 
                                      ,ed129_i_inicio 
                                      ,ed129_i_final 
                                      ,ed129_i_ultatualizse 
                                      ,ed129_i_ultatualizes 
                                      ,ed129_i_numinicio 
                                      ,ed129_i_numfinal 
                                      ,ed129_c_ativo 
                                      ,ed129_c_ulttransacao 
                       )
                values (
                                $this->ed129_i_codigo 
                               ,$this->ed129_i_escola 
                               ,$this->ed129_i_inicio 
                               ,$this->ed129_i_final 
                               ,$this->ed129_i_ultatualizse 
                               ,$this->ed129_i_ultatualizes 
                               ,$this->ed129_i_numinicio 
                               ,$this->ed129_i_numfinal 
                               ,'$this->ed129_c_ativo' 
                               ,'$this->ed129_c_ulttransacao' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro das Sequencias das Escolas Locais ($this->ed129_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro das Sequencias das Escolas Locais já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro das Sequencias das Escolas Locais ($this->ed129_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed129_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed129_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1009220,'$this->ed129_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1010189,1009220,'','".AddSlashes(pg_result($resaco,0,'ed129_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010189,1009221,'','".AddSlashes(pg_result($resaco,0,'ed129_i_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010189,1009222,'','".AddSlashes(pg_result($resaco,0,'ed129_i_inicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010189,1009223,'','".AddSlashes(pg_result($resaco,0,'ed129_i_final'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010189,1009224,'','".AddSlashes(pg_result($resaco,0,'ed129_i_ultatualizse'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010189,1009234,'','".AddSlashes(pg_result($resaco,0,'ed129_i_ultatualizes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010189,1009225,'','".AddSlashes(pg_result($resaco,0,'ed129_i_numinicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010189,1009226,'','".AddSlashes(pg_result($resaco,0,'ed129_i_numfinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010189,1009233,'','".AddSlashes(pg_result($resaco,0,'ed129_c_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010189,1009235,'','".AddSlashes(pg_result($resaco,0,'ed129_c_ulttransacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed129_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update escola_sequencias set ";
     $virgula = "";
     if(trim($this->ed129_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed129_i_codigo"])){ 
       $sql  .= $virgula." ed129_i_codigo = $this->ed129_i_codigo ";
       $virgula = ",";
       if(trim($this->ed129_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed129_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed129_i_escola)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed129_i_escola"])){ 
       $sql  .= $virgula." ed129_i_escola = $this->ed129_i_escola ";
       $virgula = ",";
       if(trim($this->ed129_i_escola) == null ){ 
         $this->erro_sql = " Campo Escola nao Informado.";
         $this->erro_campo = "ed129_i_escola";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed129_i_inicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed129_i_inicio"])){ 
       $sql  .= $virgula." ed129_i_inicio = $this->ed129_i_inicio ";
       $virgula = ",";
       if(trim($this->ed129_i_inicio) == null ){ 
         $this->erro_sql = " Campo Início da Sequência nao Informado.";
         $this->erro_campo = "ed129_i_inicio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed129_i_final)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed129_i_final"])){ 
       $sql  .= $virgula." ed129_i_final = $this->ed129_i_final ";
       $virgula = ",";
       if(trim($this->ed129_i_final) == null ){ 
         $this->erro_sql = " Campo Final da Sequência nao Informado.";
         $this->erro_campo = "ed129_i_final";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed129_i_ultatualizse)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed129_i_ultatualizse"])){ 
       $sql  .= $virgula." ed129_i_ultatualizse = $this->ed129_i_ultatualizse ";
       $virgula = ",";
       if(trim($this->ed129_i_ultatualizse) == null ){ 
         $this->erro_sql = " Campo Última Atualização nao Informado.";
         $this->erro_campo = "ed129_i_ultatualizse";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed129_i_ultatualizes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed129_i_ultatualizes"])){ 
       $sql  .= $virgula." ed129_i_ultatualizes = $this->ed129_i_ultatualizes ";
       $virgula = ",";
       if(trim($this->ed129_i_ultatualizes) == null ){ 
         $this->erro_sql = " Campo Última Atualização nao Informado.";
         $this->erro_campo = "ed129_i_ultatualizes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed129_i_numinicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed129_i_numinicio"])){ 
       $sql  .= $virgula." ed129_i_numinicio = $this->ed129_i_numinicio ";
       $virgula = ",";
       if(trim($this->ed129_i_numinicio) == null ){ 
         $this->erro_sql = " Campo Início da Sequência nao Informado.";
         $this->erro_campo = "ed129_i_numinicio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed129_i_numfinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed129_i_numfinal"])){ 
       $sql  .= $virgula." ed129_i_numfinal = $this->ed129_i_numfinal ";
       $virgula = ",";
       if(trim($this->ed129_i_numfinal) == null ){ 
         $this->erro_sql = " Campo Final da Sequência nao Informado.";
         $this->erro_campo = "ed129_i_numfinal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed129_c_ativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed129_c_ativo"])){ 
       $sql  .= $virgula." ed129_c_ativo = '$this->ed129_c_ativo' ";
       $virgula = ",";
       if(trim($this->ed129_c_ativo) == null ){ 
         $this->erro_sql = " Campo Ativo nao Informado.";
         $this->erro_campo = "ed129_c_ativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed129_c_ulttransacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed129_c_ulttransacao"])){ 
       $sql  .= $virgula." ed129_c_ulttransacao = '$this->ed129_c_ulttransacao' ";
       $virgula = ",";
       if(trim($this->ed129_c_ulttransacao) == null ){ 
         $this->erro_sql = " Campo Última Transação nao Informado.";
         $this->erro_campo = "ed129_c_ulttransacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed129_i_codigo!=null){
       $sql .= " ed129_i_codigo = $this->ed129_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed129_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1009220,'$this->ed129_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed129_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1010189,1009220,'".AddSlashes(pg_result($resaco,$conresaco,'ed129_i_codigo'))."','$this->ed129_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed129_i_escola"]))
           $resac = db_query("insert into db_acount values($acount,1010189,1009221,'".AddSlashes(pg_result($resaco,$conresaco,'ed129_i_escola'))."','$this->ed129_i_escola',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed129_i_inicio"]))
           $resac = db_query("insert into db_acount values($acount,1010189,1009222,'".AddSlashes(pg_result($resaco,$conresaco,'ed129_i_inicio'))."','$this->ed129_i_inicio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed129_i_final"]))
           $resac = db_query("insert into db_acount values($acount,1010189,1009223,'".AddSlashes(pg_result($resaco,$conresaco,'ed129_i_final'))."','$this->ed129_i_final',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed129_i_ultatualizse"]))
           $resac = db_query("insert into db_acount values($acount,1010189,1009224,'".AddSlashes(pg_result($resaco,$conresaco,'ed129_i_ultatualizse'))."','$this->ed129_i_ultatualizse',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed129_i_ultatualizes"]))
           $resac = db_query("insert into db_acount values($acount,1010189,1009234,'".AddSlashes(pg_result($resaco,$conresaco,'ed129_i_ultatualizes'))."','$this->ed129_i_ultatualizes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed129_i_numinicio"]))
           $resac = db_query("insert into db_acount values($acount,1010189,1009225,'".AddSlashes(pg_result($resaco,$conresaco,'ed129_i_numinicio'))."','$this->ed129_i_numinicio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed129_i_numfinal"]))
           $resac = db_query("insert into db_acount values($acount,1010189,1009226,'".AddSlashes(pg_result($resaco,$conresaco,'ed129_i_numfinal'))."','$this->ed129_i_numfinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed129_c_ativo"]))
           $resac = db_query("insert into db_acount values($acount,1010189,1009233,'".AddSlashes(pg_result($resaco,$conresaco,'ed129_c_ativo'))."','$this->ed129_c_ativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed129_c_ulttransacao"]))
           $resac = db_query("insert into db_acount values($acount,1010189,1009235,'".AddSlashes(pg_result($resaco,$conresaco,'ed129_c_ulttransacao'))."','$this->ed129_c_ulttransacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro das Sequencias das Escolas Locais nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed129_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro das Sequencias das Escolas Locais nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed129_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed129_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed129_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed129_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1009220,'$ed129_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1010189,1009220,'','".AddSlashes(pg_result($resaco,$iresaco,'ed129_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010189,1009221,'','".AddSlashes(pg_result($resaco,$iresaco,'ed129_i_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010189,1009222,'','".AddSlashes(pg_result($resaco,$iresaco,'ed129_i_inicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010189,1009223,'','".AddSlashes(pg_result($resaco,$iresaco,'ed129_i_final'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010189,1009224,'','".AddSlashes(pg_result($resaco,$iresaco,'ed129_i_ultatualizse'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010189,1009234,'','".AddSlashes(pg_result($resaco,$iresaco,'ed129_i_ultatualizes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010189,1009225,'','".AddSlashes(pg_result($resaco,$iresaco,'ed129_i_numinicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010189,1009226,'','".AddSlashes(pg_result($resaco,$iresaco,'ed129_i_numfinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010189,1009233,'','".AddSlashes(pg_result($resaco,$iresaco,'ed129_c_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010189,1009235,'','".AddSlashes(pg_result($resaco,$iresaco,'ed129_c_ulttransacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from escola_sequencias
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed129_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed129_i_codigo = $ed129_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro das Sequencias das Escolas Locais nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed129_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro das Sequencias das Escolas Locais nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed129_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed129_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:escola_sequencias";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $ed129_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from escola_sequencias ";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = escola_sequencias.ed129_i_escola";
     $sql .= "      inner join bairro  on  bairro.j13_codi = escola.ed18_i_bairro";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = escola.ed18_i_rua";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = escola.ed18_i_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($ed129_i_codigo!=null ){
         $sql2 .= " where escola_sequencias.ed129_i_codigo = $ed129_i_codigo "; 
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
   function sql_query_file ( $ed129_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from escola_sequencias ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed129_i_codigo!=null ){
         $sql2 .= " where escola_sequencias.ed129_i_codigo = $ed129_i_codigo "; 
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