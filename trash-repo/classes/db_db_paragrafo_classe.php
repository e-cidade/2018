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

//MODULO: configuracoes
//CLASSE DA ENTIDADE db_paragrafo
class cl_db_paragrafo { 
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
   var $db02_idparag = 0; 
   var $db02_descr = null; 
   var $db02_texto = null; 
   var $db02_alinha = 0; 
   var $db02_inicia = 0; 
   var $db02_espaca = 0; 
   var $db02_alinhamento = null; 
   var $db02_altura = 0; 
   var $db02_largura = 0; 
   var $db02_tipo = 0; 
   var $db02_instit = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db02_idparag = int4 = Código do parágrafo 
                 db02_descr = varchar(100) = Parágrafo 
                 db02_texto = text = Texto 
                 db02_alinha = int4 = Alinhamento 
                 db02_inicia = int4 = Inicio da linha 
                 db02_espaca = int4 = Espaçamento entre linhas 
                 db02_alinhamento = char(1) = Alinhamento 
                 db02_altura = float4 = Altura da linha 
                 db02_largura = float4 = Largura da linha 
                 db02_tipo = int4 = Tipo de paragrafo 
                 db02_instit = int4 = Instituição 
                 ";
   //funcao construtor da classe 
   function cl_db_paragrafo() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_paragrafo"); 
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
       $this->db02_idparag = ($this->db02_idparag == ""?@$GLOBALS["HTTP_POST_VARS"]["db02_idparag"]:$this->db02_idparag);
       $this->db02_descr = ($this->db02_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["db02_descr"]:$this->db02_descr);
       $this->db02_texto = ($this->db02_texto == ""?@$GLOBALS["HTTP_POST_VARS"]["db02_texto"]:$this->db02_texto);
       $this->db02_alinha = ($this->db02_alinha == ""?@$GLOBALS["HTTP_POST_VARS"]["db02_alinha"]:$this->db02_alinha);
       $this->db02_inicia = ($this->db02_inicia == ""?@$GLOBALS["HTTP_POST_VARS"]["db02_inicia"]:$this->db02_inicia);
       $this->db02_espaca = ($this->db02_espaca == ""?@$GLOBALS["HTTP_POST_VARS"]["db02_espaca"]:$this->db02_espaca);
       $this->db02_alinhamento = ($this->db02_alinhamento == ""?@$GLOBALS["HTTP_POST_VARS"]["db02_alinhamento"]:$this->db02_alinhamento);
       $this->db02_altura = ($this->db02_altura == ""?@$GLOBALS["HTTP_POST_VARS"]["db02_altura"]:$this->db02_altura);
       $this->db02_largura = ($this->db02_largura == ""?@$GLOBALS["HTTP_POST_VARS"]["db02_largura"]:$this->db02_largura);
       $this->db02_tipo = ($this->db02_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["db02_tipo"]:$this->db02_tipo);
       $this->db02_instit = ($this->db02_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["db02_instit"]:$this->db02_instit);
     }else{
       $this->db02_idparag = ($this->db02_idparag == ""?@$GLOBALS["HTTP_POST_VARS"]["db02_idparag"]:$this->db02_idparag);
     }
   }
   // funcao para inclusao
   function incluir ($db02_idparag){ 
      $this->atualizacampos();
     if($this->db02_descr == null ){ 
       $this->erro_sql = " Campo Parágrafo nao Informado.";
       $this->erro_campo = "db02_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db02_alinha == null ){ 
       $this->erro_sql = " Campo Alinhamento nao Informado.";
       $this->erro_campo = "db02_alinha";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db02_inicia == null ){ 
       $this->erro_sql = " Campo Inicio da linha nao Informado.";
       $this->erro_campo = "db02_inicia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db02_espaca == null ){ 
       $this->erro_sql = " Campo Espaçamento entre linhas nao Informado.";
       $this->erro_campo = "db02_espaca";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db02_alinhamento == null ){ 
       $this->erro_sql = " Campo Alinhamento nao Informado.";
       $this->erro_campo = "db02_alinhamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db02_altura == null ){ 
       $this->erro_sql = " Campo Altura da linha nao Informado.";
       $this->erro_campo = "db02_altura";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db02_largura == null ){ 
       $this->erro_sql = " Campo Largura da linha nao Informado.";
       $this->erro_campo = "db02_largura";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db02_tipo == null ){ 
       $this->erro_sql = " Campo Tipo de paragrafo nao Informado.";
       $this->erro_campo = "db02_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db02_instit == null ){ 
       $this->erro_sql = " Campo Instituição nao Informado.";
       $this->erro_campo = "db02_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($db02_idparag == "" || $db02_idparag == null ){
       $result = db_query("select nextval('db_paragrafo_db02_idparag_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: db_paragrafo_db02_idparag_seq do campo: db02_idparag"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->db02_idparag = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from db_paragrafo_db02_idparag_seq");
       if(($result != false) && (pg_result($result,0,0) < $db02_idparag)){
         $this->erro_sql = " Campo db02_idparag maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->db02_idparag = $db02_idparag; 
       }
     }
     if(($this->db02_idparag == null) || ($this->db02_idparag == "") ){ 
       $this->erro_sql = " Campo db02_idparag nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_paragrafo(
                                       db02_idparag 
                                      ,db02_descr 
                                      ,db02_texto 
                                      ,db02_alinha 
                                      ,db02_inicia 
                                      ,db02_espaca 
                                      ,db02_alinhamento 
                                      ,db02_altura 
                                      ,db02_largura 
                                      ,db02_tipo 
                                      ,db02_instit 
                       )
                values (
                                $this->db02_idparag 
                               ,'$this->db02_descr' 
                               ,'$this->db02_texto' 
                               ,$this->db02_alinha 
                               ,$this->db02_inicia 
                               ,$this->db02_espaca 
                               ,'$this->db02_alinhamento' 
                               ,$this->db02_altura 
                               ,$this->db02_largura 
                               ,$this->db02_tipo 
                               ,$this->db02_instit 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Tabela com parágrafos ($this->db02_idparag) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Tabela com parágrafos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Tabela com parágrafos ($this->db02_idparag) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db02_idparag;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->db02_idparag));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,3588,'$this->db02_idparag','I')");
       $resac = db_query("insert into db_acount values($acount,517,3588,'','".AddSlashes(pg_result($resaco,0,'db02_idparag'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,517,3589,'','".AddSlashes(pg_result($resaco,0,'db02_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,517,3590,'','".AddSlashes(pg_result($resaco,0,'db02_texto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,517,3591,'','".AddSlashes(pg_result($resaco,0,'db02_alinha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,517,3592,'','".AddSlashes(pg_result($resaco,0,'db02_inicia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,517,3604,'','".AddSlashes(pg_result($resaco,0,'db02_espaca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,517,11066,'','".AddSlashes(pg_result($resaco,0,'db02_alinhamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,517,11064,'','".AddSlashes(pg_result($resaco,0,'db02_altura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,517,11065,'','".AddSlashes(pg_result($resaco,0,'db02_largura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,517,11067,'','".AddSlashes(pg_result($resaco,0,'db02_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,517,11313,'','".AddSlashes(pg_result($resaco,0,'db02_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($db02_idparag=null) { 
      $this->atualizacampos();
     $sql = " update db_paragrafo set ";
     $virgula = "";
     if(trim($this->db02_idparag)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db02_idparag"])){ 
       $sql  .= $virgula." db02_idparag = $this->db02_idparag ";
       $virgula = ",";
       if(trim($this->db02_idparag) == null ){ 
         $this->erro_sql = " Campo Código do parágrafo nao Informado.";
         $this->erro_campo = "db02_idparag";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db02_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db02_descr"])){ 
       $sql  .= $virgula." db02_descr = '$this->db02_descr' ";
       $virgula = ",";
       if(trim($this->db02_descr) == null ){ 
         $this->erro_sql = " Campo Parágrafo nao Informado.";
         $this->erro_campo = "db02_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db02_texto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db02_texto"])){ 
       $sql  .= $virgula." db02_texto = '$this->db02_texto' ";
       $virgula = ",";
     }
     if(trim($this->db02_alinha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db02_alinha"])){ 
       $sql  .= $virgula." db02_alinha = $this->db02_alinha ";
       $virgula = ",";
       if(trim($this->db02_alinha) == null ){ 
         $this->erro_sql = " Campo Alinhamento nao Informado.";
         $this->erro_campo = "db02_alinha";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db02_inicia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db02_inicia"])){ 
       $sql  .= $virgula." db02_inicia = $this->db02_inicia ";
       $virgula = ",";
       if(trim($this->db02_inicia) == null ){ 
         $this->erro_sql = " Campo Inicio da linha nao Informado.";
         $this->erro_campo = "db02_inicia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db02_espaca)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db02_espaca"])){ 
       $sql  .= $virgula." db02_espaca = $this->db02_espaca ";
       $virgula = ",";
       if(trim($this->db02_espaca) == null ){ 
         $this->erro_sql = " Campo Espaçamento entre linhas nao Informado.";
         $this->erro_campo = "db02_espaca";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db02_alinhamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db02_alinhamento"])){ 
       $sql  .= $virgula." db02_alinhamento = '$this->db02_alinhamento' ";
       $virgula = ",";
       if(trim($this->db02_alinhamento) == null ){ 
         $this->erro_sql = " Campo Alinhamento nao Informado.";
         $this->erro_campo = "db02_alinhamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db02_altura)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db02_altura"])){ 
       $sql  .= $virgula." db02_altura = $this->db02_altura ";
       $virgula = ",";
       if(trim($this->db02_altura) == null ){ 
         $this->erro_sql = " Campo Altura da linha nao Informado.";
         $this->erro_campo = "db02_altura";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db02_largura)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db02_largura"])){ 
       $sql  .= $virgula." db02_largura = $this->db02_largura ";
       $virgula = ",";
       if(trim($this->db02_largura) == null ){ 
         $this->erro_sql = " Campo Largura da linha nao Informado.";
         $this->erro_campo = "db02_largura";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db02_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db02_tipo"])){ 
       $sql  .= $virgula." db02_tipo = $this->db02_tipo ";
       $virgula = ",";
       if(trim($this->db02_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo de paragrafo nao Informado.";
         $this->erro_campo = "db02_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db02_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db02_instit"])){ 
       $sql  .= $virgula." db02_instit = $this->db02_instit ";
       $virgula = ",";
       if(trim($this->db02_instit) == null ){ 
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "db02_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($db02_idparag!=null){
       $sql .= " db02_idparag = $this->db02_idparag";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->db02_idparag));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3588,'$this->db02_idparag','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db02_idparag"]))
           $resac = db_query("insert into db_acount values($acount,517,3588,'".AddSlashes(pg_result($resaco,$conresaco,'db02_idparag'))."','$this->db02_idparag',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db02_descr"]))
           $resac = db_query("insert into db_acount values($acount,517,3589,'".AddSlashes(pg_result($resaco,$conresaco,'db02_descr'))."','$this->db02_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db02_texto"]))
           $resac = db_query("insert into db_acount values($acount,517,3590,'".AddSlashes(pg_result($resaco,$conresaco,'db02_texto'))."','$this->db02_texto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db02_alinha"]))
           $resac = db_query("insert into db_acount values($acount,517,3591,'".AddSlashes(pg_result($resaco,$conresaco,'db02_alinha'))."','$this->db02_alinha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db02_inicia"]))
           $resac = db_query("insert into db_acount values($acount,517,3592,'".AddSlashes(pg_result($resaco,$conresaco,'db02_inicia'))."','$this->db02_inicia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db02_espaca"]))
           $resac = db_query("insert into db_acount values($acount,517,3604,'".AddSlashes(pg_result($resaco,$conresaco,'db02_espaca'))."','$this->db02_espaca',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db02_alinhamento"]))
           $resac = db_query("insert into db_acount values($acount,517,11066,'".AddSlashes(pg_result($resaco,$conresaco,'db02_alinhamento'))."','$this->db02_alinhamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db02_altura"]))
           $resac = db_query("insert into db_acount values($acount,517,11064,'".AddSlashes(pg_result($resaco,$conresaco,'db02_altura'))."','$this->db02_altura',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db02_largura"]))
           $resac = db_query("insert into db_acount values($acount,517,11065,'".AddSlashes(pg_result($resaco,$conresaco,'db02_largura'))."','$this->db02_largura',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db02_tipo"]))
           $resac = db_query("insert into db_acount values($acount,517,11067,'".AddSlashes(pg_result($resaco,$conresaco,'db02_tipo'))."','$this->db02_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db02_instit"]))
           $resac = db_query("insert into db_acount values($acount,517,11313,'".AddSlashes(pg_result($resaco,$conresaco,'db02_instit'))."','$this->db02_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tabela com parágrafos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db02_idparag;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tabela com parágrafos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db02_idparag;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db02_idparag;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($db02_idparag=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($db02_idparag));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3588,'$db02_idparag','E')");
         $resac = db_query("insert into db_acount values($acount,517,3588,'','".AddSlashes(pg_result($resaco,$iresaco,'db02_idparag'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,517,3589,'','".AddSlashes(pg_result($resaco,$iresaco,'db02_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,517,3590,'','".AddSlashes(pg_result($resaco,$iresaco,'db02_texto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,517,3591,'','".AddSlashes(pg_result($resaco,$iresaco,'db02_alinha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,517,3592,'','".AddSlashes(pg_result($resaco,$iresaco,'db02_inicia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,517,3604,'','".AddSlashes(pg_result($resaco,$iresaco,'db02_espaca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,517,11066,'','".AddSlashes(pg_result($resaco,$iresaco,'db02_alinhamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,517,11064,'','".AddSlashes(pg_result($resaco,$iresaco,'db02_altura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,517,11065,'','".AddSlashes(pg_result($resaco,$iresaco,'db02_largura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,517,11067,'','".AddSlashes(pg_result($resaco,$iresaco,'db02_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,517,11313,'','".AddSlashes(pg_result($resaco,$iresaco,'db02_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_paragrafo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($db02_idparag != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db02_idparag = $db02_idparag ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tabela com parágrafos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db02_idparag;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tabela com parágrafos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db02_idparag;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db02_idparag;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_paragrafo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $db02_idparag=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_paragrafo ";
     $sql .= "      inner join db_config  on  db_config.codigo = db_paragrafo.db02_instit";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($db02_idparag!=null ){
         $sql2 .= " where db_paragrafo.db02_idparag = $db02_idparag "; 
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
   function sql_query_file ( $db02_idparag=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_paragrafo ";
     $sql2 = "";
     if($dbwhere==""){
       if($db02_idparag!=null ){
         $sql2 .= " where db_paragrafo.db02_idparag = $db02_idparag "; 
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