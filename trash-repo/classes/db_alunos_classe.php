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
//CLASSE DA ENTIDADE alunos
class cl_alunos { 
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
   var $ed07_i_codigo = 0; 
   var $ed07_c_senha = null; 
   var $ed07_c_necessidades = null; 
   var $ed07_c_foto = null; 
   var $ed07_t_descr = null; 
   var $ed07_i_responsavel = 0; 
   var $ed07_c_certidao = null; 
   var $ed07_c_cartorio = null; 
   var $ed07_c_livro = null; 
   var $ed07_c_folha = null; 
   var $ed07_d_datacert_dia = null; 
   var $ed07_d_datacert_mes = null; 
   var $ed07_d_datacert_ano = null; 
   var $ed07_d_datacert = null; 
   var $ed07_t_pendentes = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed07_i_codigo = int4 = CGM do Aluno 
                 ed07_c_senha = char(32) = Senha Internet 
                 ed07_c_necessidades = char(50) = Necessidades Especiais 
                 ed07_c_foto = char(50) = Foto 
                 ed07_t_descr = text = Descrição 
                 ed07_i_responsavel = int8 = Responsável 
                 ed07_c_certidao = char(10) = Certidão 
                 ed07_c_cartorio = char(50) = Cartório 
                 ed07_c_livro = char(5) = Livro 
                 ed07_c_folha = char(5) = Folha 
                 ed07_d_datacert = date = Data da Certidao 
                 ed07_t_pendentes = text = Pendentes 
                 ";
   //funcao construtor da classe 
   function cl_alunos() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("alunos"); 
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
       $this->ed07_i_codigo = ($this->ed07_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed07_i_codigo"]:$this->ed07_i_codigo);
       $this->ed07_c_senha = ($this->ed07_c_senha == ""?@$GLOBALS["HTTP_POST_VARS"]["ed07_c_senha"]:$this->ed07_c_senha);
       $this->ed07_c_necessidades = ($this->ed07_c_necessidades == ""?@$GLOBALS["HTTP_POST_VARS"]["ed07_c_necessidades"]:$this->ed07_c_necessidades);
       $this->ed07_c_foto = ($this->ed07_c_foto == ""?@$GLOBALS["HTTP_POST_VARS"]["ed07_c_foto"]:$this->ed07_c_foto);
       $this->ed07_t_descr = ($this->ed07_t_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["ed07_t_descr"]:$this->ed07_t_descr);
       $this->ed07_i_responsavel = ($this->ed07_i_responsavel == ""?@$GLOBALS["HTTP_POST_VARS"]["ed07_i_responsavel"]:$this->ed07_i_responsavel);
       $this->ed07_c_certidao = ($this->ed07_c_certidao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed07_c_certidao"]:$this->ed07_c_certidao);
       $this->ed07_c_cartorio = ($this->ed07_c_cartorio == ""?@$GLOBALS["HTTP_POST_VARS"]["ed07_c_cartorio"]:$this->ed07_c_cartorio);
       $this->ed07_c_livro = ($this->ed07_c_livro == ""?@$GLOBALS["HTTP_POST_VARS"]["ed07_c_livro"]:$this->ed07_c_livro);
       $this->ed07_c_folha = ($this->ed07_c_folha == ""?@$GLOBALS["HTTP_POST_VARS"]["ed07_c_folha"]:$this->ed07_c_folha);
       if($this->ed07_d_datacert == ""){
         $this->ed07_d_datacert_dia = ($this->ed07_d_datacert_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed07_d_datacert_dia"]:$this->ed07_d_datacert_dia);
         $this->ed07_d_datacert_mes = ($this->ed07_d_datacert_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed07_d_datacert_mes"]:$this->ed07_d_datacert_mes);
         $this->ed07_d_datacert_ano = ($this->ed07_d_datacert_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed07_d_datacert_ano"]:$this->ed07_d_datacert_ano);
         if($this->ed07_d_datacert_dia != ""){
            $this->ed07_d_datacert = $this->ed07_d_datacert_ano."-".$this->ed07_d_datacert_mes."-".$this->ed07_d_datacert_dia;
         }
       }
       $this->ed07_t_pendentes = ($this->ed07_t_pendentes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed07_t_pendentes"]:$this->ed07_t_pendentes);
     }else{
       $this->ed07_i_codigo = ($this->ed07_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed07_i_codigo"]:$this->ed07_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed07_i_codigo){ 
      $this->atualizacampos();
     if($this->ed07_c_senha == null ){ 
       $this->erro_sql = " Campo Senha Internet nao Informado.";
       $this->erro_campo = "ed07_c_senha";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed07_c_necessidades == null ){ 
       $this->erro_sql = " Campo Necessidades Especiais nao Informado.";
       $this->erro_campo = "ed07_c_necessidades";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed07_t_descr == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "ed07_t_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed07_i_responsavel == null ){ 
       $this->erro_sql = " Campo Responsável nao Informado.";
       $this->erro_campo = "ed07_i_responsavel";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed07_c_certidao == null ){ 
       $this->erro_sql = " Campo Certidão nao Informado.";
       $this->erro_campo = "ed07_c_certidao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed07_c_cartorio == null ){ 
       $this->erro_sql = " Campo Cartório nao Informado.";
       $this->erro_campo = "ed07_c_cartorio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed07_c_livro == null ){ 
       $this->erro_sql = " Campo Livro nao Informado.";
       $this->erro_campo = "ed07_c_livro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed07_c_folha == null ){ 
       $this->erro_sql = " Campo Folha nao Informado.";
       $this->erro_campo = "ed07_c_folha";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed07_d_datacert == null ){ 
       $this->erro_sql = " Campo Data da Certidao nao Informado.";
       $this->erro_campo = "ed07_d_datacert_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed07_t_pendentes == null ){ 
       $this->erro_sql = " Campo Pendentes nao Informado.";
       $this->erro_campo = "ed07_t_pendentes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->ed07_i_codigo = $ed07_i_codigo; 
     if(($this->ed07_i_codigo == null) || ($this->ed07_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed07_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into alunos(
                                       ed07_i_codigo 
                                      ,ed07_c_senha 
                                      ,ed07_c_necessidades 
                                      ,ed07_c_foto 
                                      ,ed07_t_descr 
                                      ,ed07_i_responsavel 
                                      ,ed07_c_certidao 
                                      ,ed07_c_cartorio 
                                      ,ed07_c_livro 
                                      ,ed07_c_folha 
                                      ,ed07_d_datacert 
                                      ,ed07_t_pendentes 
                       )
                values (
                                $this->ed07_i_codigo 
                               ,'$this->ed07_c_senha' 
                               ,'$this->ed07_c_necessidades' 
                               ,'$this->ed07_c_foto' 
                               ,'$this->ed07_t_descr' 
                               ,$this->ed07_i_responsavel 
                               ,'$this->ed07_c_certidao' 
                               ,'$this->ed07_c_cartorio' 
                               ,'$this->ed07_c_livro' 
                               ,'$this->ed07_c_folha' 
                               ,".($this->ed07_d_datacert == "null" || $this->ed07_d_datacert == ""?"null":"'".$this->ed07_d_datacert."'")." 
                               ,'$this->ed07_t_pendentes' 
                      )";
     $result = @pg_exec($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Alunos ($this->ed07_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Alunos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Alunos ($this->ed07_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed07_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed07_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,1005011,'$this->ed07_i_codigo','I')");
       $resac = pg_query("insert into db_acount values($acount,1005007,1005011,'','".AddSlashes(pg_result($resaco,0,'ed07_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1005007,1006041,'','".AddSlashes(pg_result($resaco,0,'ed07_c_senha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1005007,1006084,'','".AddSlashes(pg_result($resaco,0,'ed07_c_necessidades'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1005007,1006085,'','".AddSlashes(pg_result($resaco,0,'ed07_c_foto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1005007,1006086,'','".AddSlashes(pg_result($resaco,0,'ed07_t_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1005007,1006099,'','".AddSlashes(pg_result($resaco,0,'ed07_i_responsavel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1005007,1006101,'','".AddSlashes(pg_result($resaco,0,'ed07_c_certidao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1005007,1006104,'','".AddSlashes(pg_result($resaco,0,'ed07_c_cartorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1005007,1006102,'','".AddSlashes(pg_result($resaco,0,'ed07_c_livro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1005007,1006103,'','".AddSlashes(pg_result($resaco,0,'ed07_c_folha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1005007,1006106,'','".AddSlashes(pg_result($resaco,0,'ed07_d_datacert'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1005007,1006105,'','".AddSlashes(pg_result($resaco,0,'ed07_t_pendentes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed07_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update alunos set ";
     $virgula = "";
     if(trim($this->ed07_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed07_i_codigo"])){ 
       $sql  .= $virgula." ed07_i_codigo = $this->ed07_i_codigo ";
       $virgula = ",";
       if(trim($this->ed07_i_codigo) == null ){ 
         $this->erro_sql = " Campo CGM do Aluno nao Informado.";
         $this->erro_campo = "ed07_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed07_c_senha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed07_c_senha"])){ 
       $sql  .= $virgula." ed07_c_senha = '$this->ed07_c_senha' ";
       $virgula = ",";
       if(trim($this->ed07_c_senha) == null ){ 
         $this->erro_sql = " Campo Senha Internet nao Informado.";
         $this->erro_campo = "ed07_c_senha";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed07_c_necessidades)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed07_c_necessidades"])){ 
       $sql  .= $virgula." ed07_c_necessidades = '$this->ed07_c_necessidades' ";
       $virgula = ",";
       if(trim($this->ed07_c_necessidades) == null ){ 
         $this->erro_sql = " Campo Necessidades Especiais nao Informado.";
         $this->erro_campo = "ed07_c_necessidades";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed07_c_foto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed07_c_foto"])){ 
       $sql  .= $virgula." ed07_c_foto = '$this->ed07_c_foto' ";
       $virgula = ",";
     }
     if(trim($this->ed07_t_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed07_t_descr"])){ 
       $sql  .= $virgula." ed07_t_descr = '$this->ed07_t_descr' ";
       $virgula = ",";
       if(trim($this->ed07_t_descr) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "ed07_t_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed07_i_responsavel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed07_i_responsavel"])){ 
       $sql  .= $virgula." ed07_i_responsavel = $this->ed07_i_responsavel ";
       $virgula = ",";
       if(trim($this->ed07_i_responsavel) == null ){ 
         $this->erro_sql = " Campo Responsável nao Informado.";
         $this->erro_campo = "ed07_i_responsavel";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed07_c_certidao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed07_c_certidao"])){ 
       $sql  .= $virgula." ed07_c_certidao = '$this->ed07_c_certidao' ";
       $virgula = ",";
       if(trim($this->ed07_c_certidao) == null ){ 
         $this->erro_sql = " Campo Certidão nao Informado.";
         $this->erro_campo = "ed07_c_certidao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed07_c_cartorio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed07_c_cartorio"])){ 
       $sql  .= $virgula." ed07_c_cartorio = '$this->ed07_c_cartorio' ";
       $virgula = ",";
       if(trim($this->ed07_c_cartorio) == null ){ 
         $this->erro_sql = " Campo Cartório nao Informado.";
         $this->erro_campo = "ed07_c_cartorio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed07_c_livro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed07_c_livro"])){ 
       $sql  .= $virgula." ed07_c_livro = '$this->ed07_c_livro' ";
       $virgula = ",";
       if(trim($this->ed07_c_livro) == null ){ 
         $this->erro_sql = " Campo Livro nao Informado.";
         $this->erro_campo = "ed07_c_livro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed07_c_folha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed07_c_folha"])){ 
       $sql  .= $virgula." ed07_c_folha = '$this->ed07_c_folha' ";
       $virgula = ",";
       if(trim($this->ed07_c_folha) == null ){ 
         $this->erro_sql = " Campo Folha nao Informado.";
         $this->erro_campo = "ed07_c_folha";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed07_d_datacert)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed07_d_datacert_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed07_d_datacert_dia"] !="") ){ 
       $sql  .= $virgula." ed07_d_datacert = '$this->ed07_d_datacert' ";
       $virgula = ",";
       if(trim($this->ed07_d_datacert) == null ){ 
         $this->erro_sql = " Campo Data da Certidao nao Informado.";
         $this->erro_campo = "ed07_d_datacert_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed07_d_datacert_dia"])){ 
         $sql  .= $virgula." ed07_d_datacert = null ";
         $virgula = ",";
         if(trim($this->ed07_d_datacert) == null ){ 
           $this->erro_sql = " Campo Data da Certidao nao Informado.";
           $this->erro_campo = "ed07_d_datacert_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ed07_t_pendentes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed07_t_pendentes"])){ 
       $sql  .= $virgula." ed07_t_pendentes = '$this->ed07_t_pendentes' ";
       $virgula = ",";
       if(trim($this->ed07_t_pendentes) == null ){ 
         $this->erro_sql = " Campo Pendentes nao Informado.";
         $this->erro_campo = "ed07_t_pendentes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed07_i_codigo!=null){
       $sql .= " ed07_i_codigo = $this->ed07_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed07_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,1005011,'$this->ed07_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed07_i_codigo"]))
           $resac = pg_query("insert into db_acount values($acount,1005007,1005011,'".AddSlashes(pg_result($resaco,$conresaco,'ed07_i_codigo'))."','$this->ed07_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed07_c_senha"]))
           $resac = pg_query("insert into db_acount values($acount,1005007,1006041,'".AddSlashes(pg_result($resaco,$conresaco,'ed07_c_senha'))."','$this->ed07_c_senha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed07_c_necessidades"]))
           $resac = pg_query("insert into db_acount values($acount,1005007,1006084,'".AddSlashes(pg_result($resaco,$conresaco,'ed07_c_necessidades'))."','$this->ed07_c_necessidades',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed07_c_foto"]))
           $resac = pg_query("insert into db_acount values($acount,1005007,1006085,'".AddSlashes(pg_result($resaco,$conresaco,'ed07_c_foto'))."','$this->ed07_c_foto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed07_t_descr"]))
           $resac = pg_query("insert into db_acount values($acount,1005007,1006086,'".AddSlashes(pg_result($resaco,$conresaco,'ed07_t_descr'))."','$this->ed07_t_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed07_i_responsavel"]))
           $resac = pg_query("insert into db_acount values($acount,1005007,1006099,'".AddSlashes(pg_result($resaco,$conresaco,'ed07_i_responsavel'))."','$this->ed07_i_responsavel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed07_c_certidao"]))
           $resac = pg_query("insert into db_acount values($acount,1005007,1006101,'".AddSlashes(pg_result($resaco,$conresaco,'ed07_c_certidao'))."','$this->ed07_c_certidao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed07_c_cartorio"]))
           $resac = pg_query("insert into db_acount values($acount,1005007,1006104,'".AddSlashes(pg_result($resaco,$conresaco,'ed07_c_cartorio'))."','$this->ed07_c_cartorio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed07_c_livro"]))
           $resac = pg_query("insert into db_acount values($acount,1005007,1006102,'".AddSlashes(pg_result($resaco,$conresaco,'ed07_c_livro'))."','$this->ed07_c_livro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed07_c_folha"]))
           $resac = pg_query("insert into db_acount values($acount,1005007,1006103,'".AddSlashes(pg_result($resaco,$conresaco,'ed07_c_folha'))."','$this->ed07_c_folha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed07_d_datacert"]))
           $resac = pg_query("insert into db_acount values($acount,1005007,1006106,'".AddSlashes(pg_result($resaco,$conresaco,'ed07_d_datacert'))."','$this->ed07_d_datacert',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed07_t_pendentes"]))
           $resac = pg_query("insert into db_acount values($acount,1005007,1006105,'".AddSlashes(pg_result($resaco,$conresaco,'ed07_t_pendentes'))."','$this->ed07_t_pendentes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = @pg_exec($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Alunos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed07_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Alunos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed07_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed07_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed07_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed07_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,1005011,'$ed07_i_codigo','E')");
         $resac = pg_query("insert into db_acount values($acount,1005007,1005011,'','".AddSlashes(pg_result($resaco,$iresaco,'ed07_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1005007,1006041,'','".AddSlashes(pg_result($resaco,$iresaco,'ed07_c_senha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1005007,1006084,'','".AddSlashes(pg_result($resaco,$iresaco,'ed07_c_necessidades'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1005007,1006085,'','".AddSlashes(pg_result($resaco,$iresaco,'ed07_c_foto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1005007,1006086,'','".AddSlashes(pg_result($resaco,$iresaco,'ed07_t_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1005007,1006099,'','".AddSlashes(pg_result($resaco,$iresaco,'ed07_i_responsavel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1005007,1006101,'','".AddSlashes(pg_result($resaco,$iresaco,'ed07_c_certidao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1005007,1006104,'','".AddSlashes(pg_result($resaco,$iresaco,'ed07_c_cartorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1005007,1006102,'','".AddSlashes(pg_result($resaco,$iresaco,'ed07_c_livro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1005007,1006103,'','".AddSlashes(pg_result($resaco,$iresaco,'ed07_c_folha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1005007,1006106,'','".AddSlashes(pg_result($resaco,$iresaco,'ed07_d_datacert'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1005007,1006105,'','".AddSlashes(pg_result($resaco,$iresaco,'ed07_t_pendentes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from alunos
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed07_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed07_i_codigo = $ed07_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = @pg_exec($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Alunos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed07_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Alunos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed07_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed07_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:alunos";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed07_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from alunos ";
     //$sql .= "      inner join cgm  on  cgm.z01_numcgm = alunos.ed07_i_codigo ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed07_i_codigo!=null ){
         $sql2 .= " where alunos.ed07_i_codigo = $ed07_i_codigo "; 
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
   function sql_query_file ( $ed07_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from alunos ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed07_i_codigo!=null ){
         $sql2 .= " where alunos.ed07_i_codigo = $ed07_i_codigo "; 
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