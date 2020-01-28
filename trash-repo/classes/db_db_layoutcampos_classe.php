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
//CLASSE DA ENTIDADE db_layoutcampos
class cl_db_layoutcampos { 
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
   var $db52_codigo = 0; 
   var $db52_layoutlinha = 0; 
   var $db52_nome = null; 
   var $db52_descr = null; 
   var $db52_layoutformat = 0; 
   var $db52_posicao = 0; 
   var $db52_default = null; 
   var $db52_tamanho = 0; 
   var $db52_ident = 'f'; 
   var $db52_imprimir = 'f'; 
   var $db52_alinha = null; 
   var $db52_obs = null; 
   var $db52_quebraapos = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db52_codigo = int4 = Código do campo 
                 db52_layoutlinha = int4 = Código da linha 
                 db52_nome = varchar(50) = Nome do campo 
                 db52_descr = varchar(40) = Descrição do campo 
                 db52_layoutformat = int4 = Código da formatação 
                 db52_posicao = int4 = Posição na linha 
                 db52_default = text = Valor default 
                 db52_tamanho = int4 = Espaço ocupado 
                 db52_ident = bool = Identificador da linha 
                 db52_imprimir = bool = Imprimir valor 
                 db52_alinha = varchar(1) = Alinhamento 
                 db52_obs = text = Observações 
                 db52_quebraapos = int4 = Inserir quebra 
                 ";
   //funcao construtor da classe 
   function cl_db_layoutcampos() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_layoutcampos"); 
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
       $this->db52_codigo = ($this->db52_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["db52_codigo"]:$this->db52_codigo);
       $this->db52_layoutlinha = ($this->db52_layoutlinha == ""?@$GLOBALS["HTTP_POST_VARS"]["db52_layoutlinha"]:$this->db52_layoutlinha);
       $this->db52_nome = ($this->db52_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["db52_nome"]:$this->db52_nome);
       $this->db52_descr = ($this->db52_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["db52_descr"]:$this->db52_descr);
       $this->db52_layoutformat = ($this->db52_layoutformat == ""?@$GLOBALS["HTTP_POST_VARS"]["db52_layoutformat"]:$this->db52_layoutformat);
       $this->db52_posicao = ($this->db52_posicao == ""?@$GLOBALS["HTTP_POST_VARS"]["db52_posicao"]:$this->db52_posicao);
       $this->db52_default = ($this->db52_default == ""?@$GLOBALS["HTTP_POST_VARS"]["db52_default"]:$this->db52_default);
       $this->db52_tamanho = ($this->db52_tamanho == ""?@$GLOBALS["HTTP_POST_VARS"]["db52_tamanho"]:$this->db52_tamanho);
       $this->db52_ident = ($this->db52_ident == "f"?@$GLOBALS["HTTP_POST_VARS"]["db52_ident"]:$this->db52_ident);
       $this->db52_imprimir = ($this->db52_imprimir == "f"?@$GLOBALS["HTTP_POST_VARS"]["db52_imprimir"]:$this->db52_imprimir);
       $this->db52_alinha = ($this->db52_alinha == ""?@$GLOBALS["HTTP_POST_VARS"]["db52_alinha"]:$this->db52_alinha);
       $this->db52_obs = ($this->db52_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["db52_obs"]:$this->db52_obs);
       $this->db52_quebraapos = ($this->db52_quebraapos == ""?@$GLOBALS["HTTP_POST_VARS"]["db52_quebraapos"]:$this->db52_quebraapos);
     }else{
       $this->db52_codigo = ($this->db52_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["db52_codigo"]:$this->db52_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($db52_codigo){ 
      $this->atualizacampos();
     if($this->db52_layoutlinha == null ){ 
       $this->erro_sql = " Campo Código da linha nao Informado.";
       $this->erro_campo = "db52_layoutlinha";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db52_nome == null ){ 
       $this->erro_sql = " Campo Nome do campo nao Informado.";
       $this->erro_campo = "db52_nome";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db52_descr == null ){ 
       $this->erro_sql = " Campo Descrição do campo nao Informado.";
       $this->erro_campo = "db52_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db52_layoutformat == null ){ 
       $this->erro_sql = " Campo Código da formatação nao Informado.";
       $this->erro_campo = "db52_layoutformat";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db52_posicao == null ){ 
       $this->erro_sql = " Campo Posição na linha nao Informado.";
       $this->erro_campo = "db52_posicao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db52_tamanho == null ){ 
       $this->db52_tamanho = "0";
     }
     if($this->db52_ident == null ){ 
       $this->erro_sql = " Campo Identificador da linha nao Informado.";
       $this->erro_campo = "db52_ident";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db52_imprimir == null ){ 
       $this->erro_sql = " Campo Imprimir valor nao Informado.";
       $this->erro_campo = "db52_imprimir";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db52_alinha == null ){ 
       $this->erro_sql = " Campo Alinhamento nao Informado.";
       $this->erro_campo = "db52_alinha";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db52_quebraapos == null ){ 
       $this->db52_quebraapos = "0";
     }
     if($db52_codigo == "" || $db52_codigo == null ){
       $result = db_query("select nextval('db_layoutcampos_db52_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: db_layoutcampos_db52_codigo_seq do campo: db52_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->db52_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from db_layoutcampos_db52_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $db52_codigo)){
         $this->erro_sql = " Campo db52_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->db52_codigo = $db52_codigo; 
       }
     }
     if(($this->db52_codigo == null) || ($this->db52_codigo == "") ){ 
       $this->erro_sql = " Campo db52_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_layoutcampos(
                                       db52_codigo 
                                      ,db52_layoutlinha 
                                      ,db52_nome 
                                      ,db52_descr 
                                      ,db52_layoutformat 
                                      ,db52_posicao 
                                      ,db52_default 
                                      ,db52_tamanho 
                                      ,db52_ident 
                                      ,db52_imprimir 
                                      ,db52_alinha 
                                      ,db52_obs 
                                      ,db52_quebraapos 
                       )
                values (
                                $this->db52_codigo 
                               ,$this->db52_layoutlinha 
                               ,'$this->db52_nome' 
                               ,'$this->db52_descr' 
                               ,$this->db52_layoutformat 
                               ,$this->db52_posicao 
                               ,'$this->db52_default' 
                               ,$this->db52_tamanho 
                               ,'$this->db52_ident' 
                               ,'$this->db52_imprimir' 
                               ,'$this->db52_alinha' 
                               ,'$this->db52_obs' 
                               ,$this->db52_quebraapos 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro dos campos do layout ($this->db52_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro dos campos do layout já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro dos campos do layout ($this->db52_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db52_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->db52_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9073,'$this->db52_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1555,9073,'','".AddSlashes(pg_result($resaco,0,'db52_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1555,9074,'','".AddSlashes(pg_result($resaco,0,'db52_layoutlinha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1555,9076,'','".AddSlashes(pg_result($resaco,0,'db52_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1555,9077,'','".AddSlashes(pg_result($resaco,0,'db52_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1555,9079,'','".AddSlashes(pg_result($resaco,0,'db52_layoutformat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1555,9080,'','".AddSlashes(pg_result($resaco,0,'db52_posicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1555,9088,'','".AddSlashes(pg_result($resaco,0,'db52_default'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1555,9089,'','".AddSlashes(pg_result($resaco,0,'db52_tamanho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1555,9071,'','".AddSlashes(pg_result($resaco,0,'db52_ident'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1555,9100,'','".AddSlashes(pg_result($resaco,0,'db52_imprimir'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1555,9104,'','".AddSlashes(pg_result($resaco,0,'db52_alinha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1555,9099,'','".AddSlashes(pg_result($resaco,0,'db52_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1555,9130,'','".AddSlashes(pg_result($resaco,0,'db52_quebraapos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($db52_codigo=null) { 
      $this->atualizacampos();
     $sql = " update db_layoutcampos set ";
     $virgula = "";
     if(trim($this->db52_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db52_codigo"])){ 
       $sql  .= $virgula." db52_codigo = $this->db52_codigo ";
       $virgula = ",";
       if(trim($this->db52_codigo) == null ){ 
         $this->erro_sql = " Campo Código do campo nao Informado.";
         $this->erro_campo = "db52_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db52_layoutlinha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db52_layoutlinha"])){ 
       $sql  .= $virgula." db52_layoutlinha = $this->db52_layoutlinha ";
       $virgula = ",";
       if(trim($this->db52_layoutlinha) == null ){ 
         $this->erro_sql = " Campo Código da linha nao Informado.";
         $this->erro_campo = "db52_layoutlinha";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db52_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db52_nome"])){ 
       $sql  .= $virgula." db52_nome = '$this->db52_nome' ";
       $virgula = ",";
       if(trim($this->db52_nome) == null ){ 
         $this->erro_sql = " Campo Nome do campo nao Informado.";
         $this->erro_campo = "db52_nome";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db52_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db52_descr"])){ 
       $sql  .= $virgula." db52_descr = '$this->db52_descr' ";
       $virgula = ",";
       if(trim($this->db52_descr) == null ){ 
         $this->erro_sql = " Campo Descrição do campo nao Informado.";
         $this->erro_campo = "db52_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db52_layoutformat)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db52_layoutformat"])){ 
       $sql  .= $virgula." db52_layoutformat = $this->db52_layoutformat ";
       $virgula = ",";
       if(trim($this->db52_layoutformat) == null ){ 
         $this->erro_sql = " Campo Código da formatação nao Informado.";
         $this->erro_campo = "db52_layoutformat";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db52_posicao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db52_posicao"])){ 
       $sql  .= $virgula." db52_posicao = $this->db52_posicao ";
       $virgula = ",";
       if(trim($this->db52_posicao) == null ){ 
         $this->erro_sql = " Campo Posição na linha nao Informado.";
         $this->erro_campo = "db52_posicao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db52_default)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db52_default"])){ 
       $sql  .= $virgula." db52_default = '$this->db52_default' ";
       $virgula = ",";
     }
     if(trim($this->db52_tamanho)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db52_tamanho"])){ 
        if(trim($this->db52_tamanho)=="" && isset($GLOBALS["HTTP_POST_VARS"]["db52_tamanho"])){ 
           $this->db52_tamanho = "0" ; 
        } 
       $sql  .= $virgula." db52_tamanho = $this->db52_tamanho ";
       $virgula = ",";
     }
     if(trim($this->db52_ident)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db52_ident"])){ 
       $sql  .= $virgula." db52_ident = '$this->db52_ident' ";
       $virgula = ",";
       if(trim($this->db52_ident) == null ){ 
         $this->erro_sql = " Campo Identificador da linha nao Informado.";
         $this->erro_campo = "db52_ident";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db52_imprimir)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db52_imprimir"])){ 
       $sql  .= $virgula." db52_imprimir = '$this->db52_imprimir' ";
       $virgula = ",";
       if(trim($this->db52_imprimir) == null ){ 
         $this->erro_sql = " Campo Imprimir valor nao Informado.";
         $this->erro_campo = "db52_imprimir";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db52_alinha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db52_alinha"])){ 
       $sql  .= $virgula." db52_alinha = '$this->db52_alinha' ";
       $virgula = ",";
       if(trim($this->db52_alinha) == null ){ 
         $this->erro_sql = " Campo Alinhamento nao Informado.";
         $this->erro_campo = "db52_alinha";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db52_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db52_obs"])){ 
       $sql  .= $virgula." db52_obs = '$this->db52_obs' ";
       $virgula = ",";
     }
     if(trim($this->db52_quebraapos)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db52_quebraapos"])){ 
        if(trim($this->db52_quebraapos)=="" && isset($GLOBALS["HTTP_POST_VARS"]["db52_quebraapos"])){ 
           $this->db52_quebraapos = "0" ; 
        } 
       $sql  .= $virgula." db52_quebraapos = $this->db52_quebraapos ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($db52_codigo!=null){
       $sql .= " db52_codigo = $this->db52_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->db52_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9073,'$this->db52_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db52_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1555,9073,'".AddSlashes(pg_result($resaco,$conresaco,'db52_codigo'))."','$this->db52_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db52_layoutlinha"]))
           $resac = db_query("insert into db_acount values($acount,1555,9074,'".AddSlashes(pg_result($resaco,$conresaco,'db52_layoutlinha'))."','$this->db52_layoutlinha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db52_nome"]))
           $resac = db_query("insert into db_acount values($acount,1555,9076,'".AddSlashes(pg_result($resaco,$conresaco,'db52_nome'))."','$this->db52_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db52_descr"]))
           $resac = db_query("insert into db_acount values($acount,1555,9077,'".AddSlashes(pg_result($resaco,$conresaco,'db52_descr'))."','$this->db52_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db52_layoutformat"]))
           $resac = db_query("insert into db_acount values($acount,1555,9079,'".AddSlashes(pg_result($resaco,$conresaco,'db52_layoutformat'))."','$this->db52_layoutformat',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db52_posicao"]))
           $resac = db_query("insert into db_acount values($acount,1555,9080,'".AddSlashes(pg_result($resaco,$conresaco,'db52_posicao'))."','$this->db52_posicao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db52_default"]))
           $resac = db_query("insert into db_acount values($acount,1555,9088,'".AddSlashes(pg_result($resaco,$conresaco,'db52_default'))."','$this->db52_default',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db52_tamanho"]))
           $resac = db_query("insert into db_acount values($acount,1555,9089,'".AddSlashes(pg_result($resaco,$conresaco,'db52_tamanho'))."','$this->db52_tamanho',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db52_ident"]))
           $resac = db_query("insert into db_acount values($acount,1555,9071,'".AddSlashes(pg_result($resaco,$conresaco,'db52_ident'))."','$this->db52_ident',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db52_imprimir"]))
           $resac = db_query("insert into db_acount values($acount,1555,9100,'".AddSlashes(pg_result($resaco,$conresaco,'db52_imprimir'))."','$this->db52_imprimir',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db52_alinha"]))
           $resac = db_query("insert into db_acount values($acount,1555,9104,'".AddSlashes(pg_result($resaco,$conresaco,'db52_alinha'))."','$this->db52_alinha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db52_obs"]))
           $resac = db_query("insert into db_acount values($acount,1555,9099,'".AddSlashes(pg_result($resaco,$conresaco,'db52_obs'))."','$this->db52_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db52_quebraapos"]))
           $resac = db_query("insert into db_acount values($acount,1555,9130,'".AddSlashes(pg_result($resaco,$conresaco,'db52_quebraapos'))."','$this->db52_quebraapos',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro dos campos do layout nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db52_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro dos campos do layout nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db52_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db52_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($db52_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($db52_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9073,'$db52_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1555,9073,'','".AddSlashes(pg_result($resaco,$iresaco,'db52_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1555,9074,'','".AddSlashes(pg_result($resaco,$iresaco,'db52_layoutlinha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1555,9076,'','".AddSlashes(pg_result($resaco,$iresaco,'db52_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1555,9077,'','".AddSlashes(pg_result($resaco,$iresaco,'db52_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1555,9079,'','".AddSlashes(pg_result($resaco,$iresaco,'db52_layoutformat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1555,9080,'','".AddSlashes(pg_result($resaco,$iresaco,'db52_posicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1555,9088,'','".AddSlashes(pg_result($resaco,$iresaco,'db52_default'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1555,9089,'','".AddSlashes(pg_result($resaco,$iresaco,'db52_tamanho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1555,9071,'','".AddSlashes(pg_result($resaco,$iresaco,'db52_ident'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1555,9100,'','".AddSlashes(pg_result($resaco,$iresaco,'db52_imprimir'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1555,9104,'','".AddSlashes(pg_result($resaco,$iresaco,'db52_alinha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1555,9099,'','".AddSlashes(pg_result($resaco,$iresaco,'db52_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1555,9130,'','".AddSlashes(pg_result($resaco,$iresaco,'db52_quebraapos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_layoutcampos
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($db52_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db52_codigo = $db52_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro dos campos do layout nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db52_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro dos campos do layout nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db52_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db52_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_layoutcampos";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $db52_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_layoutcampos ";
     $sql .= "      inner join db_layoutlinha  on  db_layoutlinha.db51_codigo = db_layoutcampos.db52_layoutlinha";
     $sql .= "      inner join db_layoutformat  on  db_layoutformat.db53_codigo = db_layoutcampos.db52_layoutformat";
     $sql .= "      inner join db_layouttxt  on  db_layouttxt.db50_codigo = db_layoutlinha.db51_layouttxt";
     $sql2 = "";
     if($dbwhere==""){
       if($db52_codigo!=null ){
         $sql2 .= " where db_layoutcampos.db52_codigo = $db52_codigo "; 
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
   function sql_query_file ( $db52_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_layoutcampos ";
     $sql2 = "";
     if($dbwhere==""){
       if($db52_codigo!=null ){
         $sql2 .= " where db_layoutcampos.db52_codigo = $db52_codigo "; 
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