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

//MODULO: juridico
//CLASSE DA ENTIDADE inicialdoc
class cl_inicialdoc { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $v59_codigo = 0; 
   var $v59_inicial = 0; 
   var $v59_docum = 0; 
   var $v59_objtexto = 0; 
   var $v59_dtemissao_dia = null; 
   var $v59_dtemissao_mes = null; 
   var $v59_dtemissao_ano = null; 
   var $v59_dtemissao = null; 
   var $v59_id_usuario = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 v59_codigo = int4 = Código 
                 v59_inicial = int4 = Inicial Numero 
                 v59_docum = int4 = Código 
                 v59_objtexto = oid = Petição 
                 v59_dtemissao = date = Data da emissão 
                 v59_id_usuario = int4 = Cod. Usuário 
                 ";
   //funcao construtor da classe 
   function cl_inicialdoc() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("inicialdoc"); 
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
       $this->v59_codigo = ($this->v59_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["v59_codigo"]:$this->v59_codigo);
       $this->v59_inicial = ($this->v59_inicial == ""?@$GLOBALS["HTTP_POST_VARS"]["v59_inicial"]:$this->v59_inicial);
       $this->v59_docum = ($this->v59_docum == ""?@$GLOBALS["HTTP_POST_VARS"]["v59_docum"]:$this->v59_docum);
       $this->v59_objtexto = ($this->v59_objtexto == ""?@$GLOBALS["HTTP_POST_VARS"]["v59_objtexto"]:$this->v59_objtexto);
       if($this->v59_dtemissao == ""){
         $this->v59_dtemissao_dia = @$GLOBALS["HTTP_POST_VARS"]["v59_dtemissao_dia"];
         $this->v59_dtemissao_mes = @$GLOBALS["HTTP_POST_VARS"]["v59_dtemissao_mes"];
         $this->v59_dtemissao_ano = @$GLOBALS["HTTP_POST_VARS"]["v59_dtemissao_ano"];
         if($this->v59_dtemissao_dia != ""){
            $this->v59_dtemissao = $this->v59_dtemissao_ano."-".$this->v59_dtemissao_mes."-".$this->v59_dtemissao_dia;
         }
       }
       $this->v59_id_usuario = ($this->v59_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["v59_id_usuario"]:$this->v59_id_usuario);
     }else{
       $this->v59_codigo = ($this->v59_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["v59_codigo"]:$this->v59_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($v59_codigo){ 
      $this->atualizacampos();
     if($this->v59_inicial == null ){ 
       $this->erro_sql = " Campo Inicial Numero nao Informado.";
       $this->erro_campo = "v59_inicial";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v59_docum == null ){ 
       $this->erro_sql = " Campo Código nao Informado.";
       $this->erro_campo = "v59_docum";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v59_objtexto == null ){ 
       $this->erro_sql = " Campo Petição nao Informado.";
       $this->erro_campo = "v59_objtexto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v59_dtemissao == null ){ 
       $this->erro_sql = " Campo Data da emissão nao Informado.";
       $this->erro_campo = "v59_dtemissao_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v59_id_usuario == null ){ 
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "v59_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($v59_codigo == "" || $v59_codigo == null ){
       $result = @pg_query("select nextval('inicialdoc_v59_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: inicialdoc_v59_codigo_seq do campo: v59_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->v59_codigo = pg_result($result,0,0); 
     }else{
       $result = @pg_query("select last_value from inicialdoc_v59_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $v59_codigo)){
         $this->erro_sql = " Campo v59_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->v59_codigo = $v59_codigo; 
       }
     }
     if(($this->v59_codigo == null) || ($this->v59_codigo == "") ){ 
       $this->erro_sql = " Campo v59_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $result = @pg_query("insert into inicialdoc(
                                       v59_codigo 
                                      ,v59_inicial 
                                      ,v59_docum 
                                      ,v59_objtexto 
                                      ,v59_dtemissao 
                                      ,v59_id_usuario 
                       )
                values (
                                $this->v59_codigo 
                               ,$this->v59_inicial 
                               ,$this->v59_docum 
                               ,$this->v59_objtexto 
                               ,".($this->v59_dtemissao == "null" || $this->v59_dtemissao == ""?"null":"'".$this->v59_dtemissao."'")." 
                               ,$this->v59_id_usuario 
                      )");
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Petições das iniciais ($this->v59_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Petições das iniciais já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Petições das iniciais ($this->v59_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao Efetivada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v59_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $resaco = $this->sql_record($this->sql_query_file($this->v59_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,4660,'$this->v59_codigo','I')");
       $resac = pg_query("insert into db_acount values($acount,611,4660,'','".pg_result($resaco,0,'v59_codigo')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,611,4661,'','".pg_result($resaco,0,'v59_inicial')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,611,4665,'','".pg_result($resaco,0,'v59_docum')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,611,4663,'','".pg_result($resaco,0,'v59_objtexto')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,611,4662,'','".pg_result($resaco,0,'v59_dtemissao')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,611,4664,'','".pg_result($resaco,0,'v59_id_usuario')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($v59_codigo=null) { 
      $this->atualizacampos();
     $sql = " update inicialdoc set ";
     $virgula = "";
     if(trim($this->v59_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v59_codigo"])){ 
        if(trim($this->v59_codigo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["v59_codigo"])){ 
           $this->v59_codigo = "0" ; 
        } 
       $sql  .= $virgula." v59_codigo = $this->v59_codigo ";
       $virgula = ",";
       if(trim($this->v59_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "v59_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v59_inicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v59_inicial"])){ 
        if(trim($this->v59_inicial)=="" && isset($GLOBALS["HTTP_POST_VARS"]["v59_inicial"])){ 
           $this->v59_inicial = "0" ; 
        } 
       $sql  .= $virgula." v59_inicial = $this->v59_inicial ";
       $virgula = ",";
       if(trim($this->v59_inicial) == null ){ 
         $this->erro_sql = " Campo Inicial Numero nao Informado.";
         $this->erro_campo = "v59_inicial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v59_docum)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v59_docum"])){ 
        if(trim($this->v59_docum)=="" && isset($GLOBALS["HTTP_POST_VARS"]["v59_docum"])){ 
           $this->v59_docum = "0" ; 
        } 
       $sql  .= $virgula." v59_docum = $this->v59_docum ";
       $virgula = ",";
       if(trim($this->v59_docum) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "v59_docum";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v59_objtexto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v59_objtexto"])){ 
       $sql  .= $virgula." v59_objtexto = $this->v59_objtexto ";
       $virgula = ",";
       if(trim($this->v59_objtexto) == null ){ 
         $this->erro_sql = " Campo Petição nao Informado.";
         $this->erro_campo = "v59_objtexto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v59_dtemissao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v59_dtemissao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["v59_dtemissao_dia"] !="") ){ 
       $sql  .= $virgula." v59_dtemissao = '$this->v59_dtemissao' ";
       $virgula = ",";
       if(trim($this->v59_dtemissao) == null ){ 
         $this->erro_sql = " Campo Data da emissão nao Informado.";
         $this->erro_campo = "v59_dtemissao_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["v59_dtemissao_dia"])){ 
         $sql  .= $virgula." v59_dtemissao = null ";
         $virgula = ",";
         if(trim($this->v59_dtemissao) == null ){ 
           $this->erro_sql = " Campo Data da emissão nao Informado.";
           $this->erro_campo = "v59_dtemissao_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->v59_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v59_id_usuario"])){ 
        if(trim($this->v59_id_usuario)=="" && isset($GLOBALS["HTTP_POST_VARS"]["v59_id_usuario"])){ 
           $this->v59_id_usuario = "0" ; 
        } 
       $sql  .= $virgula." v59_id_usuario = $this->v59_id_usuario ";
       $virgula = ",";
       if(trim($this->v59_id_usuario) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "v59_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where  v59_codigo = $this->v59_codigo
";
     $resaco = $this->sql_record($this->sql_query_file($this->v59_codigo));
     if($this->numrows>0){       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,4660,'$this->v59_codigo','A')");
       if(isset($GLOBALS["HTTP_POST_VARS"]["v59_codigo"]))
         $resac = pg_query("insert into db_acount values($acount,611,4660,'".pg_result($resaco,0,'v59_codigo')."','$this->v59_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["v59_inicial"]))
         $resac = pg_query("insert into db_acount values($acount,611,4661,'".pg_result($resaco,0,'v59_inicial')."','$this->v59_inicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["v59_docum"]))
         $resac = pg_query("insert into db_acount values($acount,611,4665,'".pg_result($resaco,0,'v59_docum')."','$this->v59_docum',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["v59_objtexto"]))
         $resac = pg_query("insert into db_acount values($acount,611,4663,'".pg_result($resaco,0,'v59_objtexto')."','$this->v59_objtexto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["v59_dtemissao"]))
         $resac = pg_query("insert into db_acount values($acount,611,4662,'".pg_result($resaco,0,'v59_dtemissao')."','$this->v59_dtemissao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["v59_id_usuario"]))
         $resac = pg_query("insert into db_acount values($acount,611,4664,'".pg_result($resaco,0,'v59_id_usuario')."','$this->v59_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     $result = @pg_exec($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Petições das iniciais nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->v59_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Petições das iniciais nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->v59_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração Efetivada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v59_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($v59_codigo=null) { 
     $this->atualizacampos(true);
     $resaco = $this->sql_record($this->sql_query_file($this->v59_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,4660,'".pg_result($resaco,$iresaco,'v59_codigo')."','E')");
       $resac = pg_query("insert into db_acount values($acount,611,4660,'','".pg_result($resaco,0,'v59_codigo')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,611,4661,'','".pg_result($resaco,0,'v59_inicial')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,611,4665,'','".pg_result($resaco,0,'v59_docum')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,611,4663,'','".pg_result($resaco,0,'v59_objtexto')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,611,4662,'','".pg_result($resaco,0,'v59_dtemissao')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,611,4664,'','".pg_result($resaco,0,'v59_id_usuario')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     $sql = " delete from inicialdoc
                    where ";
     $sql2 = "";
      if($this->v59_codigo != ""){
      if($sql2!=""){
        $sql2 .= " and ";
      }
      $sql2 .= " v59_codigo = $this->v59_codigo ";
}
     $result = @pg_exec($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Petições das iniciais nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$this->v59_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Petições das iniciais nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$this->v59_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão Efetivada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v59_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
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
        $this->erro_sql   = "Dados do Grupo nao Encontrado";
        $this->erro_msg   = "Usuário: \n\n ".$this->erro_sql." \n\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $v59_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from inicialdoc ";
     $sql .= "      inner join inicial  on  inicial.v50_inicial = inicialdoc.v59_inicial";
     $sql .= "      inner join db_documento  on  db_documento.db03_docum = inicialdoc.v59_docum and db03_instit = " . db_getsession("DB_instit");
     $sql .= "      inner join advog  on  advog.v57_numcgm = inicial.v50_advog";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = inicial.v50_id_login";
     $sql .= "      inner join localiza  on  localiza.v54_codlocal = inicial.v50_codlocal";
     $sql2 = "";
     if($dbwhere==""){
       if($v59_codigo!=null ){
         $sql2 .= " where inicialdoc.v59_codigo = $v59_codigo "; 
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
   function sql_query_file ( $v59_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from inicialdoc ";
     $sql2 = "";
     if($dbwhere==""){
       if($v59_codigo!=null ){
         $sql2 .= " where inicialdoc.v59_codigo = $v59_codigo "; 
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