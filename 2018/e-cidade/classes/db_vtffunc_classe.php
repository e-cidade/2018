<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: pessoal
//CLASSE DA ENTIDADE vtffunc
class cl_vtffunc { 
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
   var $r17_anousu = 0; 
   var $r17_mesusu = 0; 
   var $r17_regist = 0; 
   var $r17_codigo = null; 
   var $r17_quant = 0; 
   var $r17_lotac = null; 
   var $r17_difere = 'f'; 
   var $r17_situac = null; 
   var $r17_tipo = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 r17_anousu = int4 = Ano 
                 r17_mesusu = int4 = Mes do Exercicio 
                 r17_regist = int4 = Codigo do Funcionario 
                 r17_codigo = char(4) = Código do Vale Transporte 
                 r17_quant = int4 = Quantidade de Passagens 
                 r17_lotac = char(     4) = Lotacao do Funcionario 
                 r17_difere = boolean = Se difere cal.r922 senao r916 
                 r17_situac = char(     1) = Situacao (ativo / inativo) 
                 r17_tipo = bool = Informar o Tipo do Vale 
                 ";
   //funcao construtor da classe 
   function cl_vtffunc() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("vtffunc"); 
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
       $this->r17_anousu = ($this->r17_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r17_anousu"]:$this->r17_anousu);
       $this->r17_mesusu = ($this->r17_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r17_mesusu"]:$this->r17_mesusu);
       $this->r17_regist = ($this->r17_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["r17_regist"]:$this->r17_regist);
       $this->r17_codigo = ($this->r17_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["r17_codigo"]:$this->r17_codigo);
       $this->r17_quant = ($this->r17_quant == ""?@$GLOBALS["HTTP_POST_VARS"]["r17_quant"]:$this->r17_quant);
       $this->r17_lotac = ($this->r17_lotac == ""?@$GLOBALS["HTTP_POST_VARS"]["r17_lotac"]:$this->r17_lotac);
       $this->r17_difere = ($this->r17_difere == "f"?@$GLOBALS["HTTP_POST_VARS"]["r17_difere"]:$this->r17_difere);
       $this->r17_situac = ($this->r17_situac == ""?@$GLOBALS["HTTP_POST_VARS"]["r17_situac"]:$this->r17_situac);
       $this->r17_tipo = ($this->r17_tipo == "f"?@$GLOBALS["HTTP_POST_VARS"]["r17_tipo"]:$this->r17_tipo);
     }else{
       $this->r17_anousu = ($this->r17_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r17_anousu"]:$this->r17_anousu);
       $this->r17_mesusu = ($this->r17_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r17_mesusu"]:$this->r17_mesusu);
       $this->r17_regist = ($this->r17_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["r17_regist"]:$this->r17_regist);
       $this->r17_codigo = ($this->r17_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["r17_codigo"]:$this->r17_codigo);
       $this->r17_difere = ($this->r17_difere == "f"?@$GLOBALS["HTTP_POST_VARS"]["r17_difere"]:$this->r17_difere);
     }
   }
   // funcao para inclusao
   function incluir ($r17_anousu,$r17_mesusu,$r17_regist,$r17_codigo,$r17_difere){ 
      $this->atualizacampos();
     if($this->r17_quant == null ){ 
       $this->erro_sql = " Campo Quantidade de Passagens nao Informado.";
       $this->erro_campo = "r17_quant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r17_lotac == null ){ 
       $this->erro_sql = " Campo Lotacao do Funcionario nao Informado.";
       $this->erro_campo = "r17_lotac";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r17_situac == null ){ 
       $this->erro_sql = " Campo Situacao (ativo / inativo) nao Informado.";
       $this->erro_campo = "r17_situac";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r17_tipo == null ){ 
       $this->erro_sql = " Campo Informar o Tipo do Vale nao Informado.";
       $this->erro_campo = "r17_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->r17_anousu = $r17_anousu; 
       $this->r17_mesusu = $r17_mesusu; 
       $this->r17_regist = $r17_regist; 
       $this->r17_codigo = $r17_codigo; 
       $this->r17_difere = $r17_difere; 
     if(($this->r17_anousu == null) || ($this->r17_anousu == "") ){ 
       $this->erro_sql = " Campo r17_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r17_mesusu == null) || ($this->r17_mesusu == "") ){ 
       $this->erro_sql = " Campo r17_mesusu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r17_regist == null) || ($this->r17_regist == "") ){ 
       $this->erro_sql = " Campo r17_regist nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r17_codigo == null) || ($this->r17_codigo == "") ){ 
       $this->erro_sql = " Campo r17_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r17_difere == null) || ($this->r17_difere == "") ){ 
       $this->erro_sql = " Campo r17_difere nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into vtffunc(
                                       r17_anousu 
                                      ,r17_mesusu 
                                      ,r17_regist 
                                      ,r17_codigo 
                                      ,r17_quant 
                                      ,r17_lotac 
                                      ,r17_difere 
                                      ,r17_situac 
                                      ,r17_tipo 
                       )
                values (
                                $this->r17_anousu 
                               ,$this->r17_mesusu 
                               ,$this->r17_regist 
                               ,'$this->r17_codigo' 
                               ,$this->r17_quant 
                               ,'$this->r17_lotac' 
                               ,'$this->r17_difere' 
                               ,'$this->r17_situac' 
                               ,'$this->r17_tipo' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Funcionarios c/ Vale Transporte ($this->r17_anousu."-".$this->r17_mesusu."-".$this->r17_regist."-".$this->r17_codigo."-".$this->r17_difere) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Funcionarios c/ Vale Transporte já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Funcionarios c/ Vale Transporte ($this->r17_anousu."-".$this->r17_mesusu."-".$this->r17_regist."-".$this->r17_codigo."-".$this->r17_difere) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r17_anousu."-".$this->r17_mesusu."-".$this->r17_regist."-".$this->r17_codigo."-".$this->r17_difere;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->r17_anousu,$this->r17_mesusu,$this->r17_regist,$this->r17_codigo,$this->r17_difere));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,4539,'$this->r17_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,4540,'$this->r17_mesusu','I')");
       $resac = db_query("insert into db_acountkey values($acount,4541,'$this->r17_regist','I')");
       $resac = db_query("insert into db_acountkey values($acount,4542,'$this->r17_codigo','I')");
       $resac = db_query("insert into db_acountkey values($acount,4545,'$this->r17_difere','I')");
       $resac = db_query("insert into db_acount values($acount,601,4539,'','".AddSlashes(pg_result($resaco,0,'r17_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,601,4540,'','".AddSlashes(pg_result($resaco,0,'r17_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,601,4541,'','".AddSlashes(pg_result($resaco,0,'r17_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,601,4542,'','".AddSlashes(pg_result($resaco,0,'r17_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,601,4543,'','".AddSlashes(pg_result($resaco,0,'r17_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,601,4544,'','".AddSlashes(pg_result($resaco,0,'r17_lotac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,601,4545,'','".AddSlashes(pg_result($resaco,0,'r17_difere'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,601,4546,'','".AddSlashes(pg_result($resaco,0,'r17_situac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,601,4547,'','".AddSlashes(pg_result($resaco,0,'r17_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($r17_anousu=null,$r17_mesusu=null,$r17_regist=null,$r17_codigo=null,$r17_difere=null) { 
      $this->atualizacampos();
     $sql = " update vtffunc set ";
     $virgula = "";
     if(trim($this->r17_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r17_anousu"])){ 
       $sql  .= $virgula." r17_anousu = $this->r17_anousu ";
       $virgula = ",";
       if(trim($this->r17_anousu) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "r17_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r17_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r17_mesusu"])){ 
       $sql  .= $virgula." r17_mesusu = $this->r17_mesusu ";
       $virgula = ",";
       if(trim($this->r17_mesusu) == null ){ 
         $this->erro_sql = " Campo Mes do Exercicio nao Informado.";
         $this->erro_campo = "r17_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r17_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r17_regist"])){ 
       $sql  .= $virgula." r17_regist = $this->r17_regist ";
       $virgula = ",";
       if(trim($this->r17_regist) == null ){ 
         $this->erro_sql = " Campo Codigo do Funcionario nao Informado.";
         $this->erro_campo = "r17_regist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r17_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r17_codigo"])){ 
       $sql  .= $virgula." r17_codigo = '$this->r17_codigo' ";
       $virgula = ",";
       if(trim($this->r17_codigo) == null ){ 
         $this->erro_sql = " Campo Código do Vale Transporte nao Informado.";
         $this->erro_campo = "r17_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r17_quant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r17_quant"])){ 
       $sql  .= $virgula." r17_quant = $this->r17_quant ";
       $virgula = ",";
       if(trim($this->r17_quant) == null ){ 
         $this->erro_sql = " Campo Quantidade de Passagens nao Informado.";
         $this->erro_campo = "r17_quant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r17_lotac)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r17_lotac"])){ 
       $sql  .= $virgula." r17_lotac = '$this->r17_lotac' ";
       $virgula = ",";
       if(trim($this->r17_lotac) == null ){ 
         $this->erro_sql = " Campo Lotacao do Funcionario nao Informado.";
         $this->erro_campo = "r17_lotac";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r17_difere)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r17_difere"])){ 
       $sql  .= $virgula." r17_difere = '$this->r17_difere' ";
       $virgula = ",";
       if(trim($this->r17_difere) == null ){ 
         $this->erro_sql = " Campo Se difere cal.r922 senao r916 nao Informado.";
         $this->erro_campo = "r17_difere";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r17_situac)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r17_situac"])){ 
       $sql  .= $virgula." r17_situac = '$this->r17_situac' ";
       $virgula = ",";
       if(trim($this->r17_situac) == null ){ 
         $this->erro_sql = " Campo Situacao (ativo / inativo) nao Informado.";
         $this->erro_campo = "r17_situac";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r17_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r17_tipo"])){ 
       $sql  .= $virgula." r17_tipo = '$this->r17_tipo' ";
       $virgula = ",";
       if(trim($this->r17_tipo) == null ){ 
         $this->erro_sql = " Campo Informar o Tipo do Vale nao Informado.";
         $this->erro_campo = "r17_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($r17_anousu!=null){
       $sql .= " r17_anousu = $this->r17_anousu";
     }
     if($r17_mesusu!=null){
       $sql .= " and  r17_mesusu = $this->r17_mesusu";
     }
     if($r17_regist!=null){
       $sql .= " and  r17_regist = $this->r17_regist";
     }
     if($r17_codigo!=null){
       $sql .= " and  r17_codigo = '$this->r17_codigo'";
     }
     if($r17_difere!=null){
       $sql .= " and  r17_difere = '$this->r17_difere'";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->r17_anousu,$this->r17_mesusu,$this->r17_regist,$this->r17_codigo,$this->r17_difere));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4539,'$this->r17_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,4540,'$this->r17_mesusu','A')");
         $resac = db_query("insert into db_acountkey values($acount,4541,'$this->r17_regist','A')");
         $resac = db_query("insert into db_acountkey values($acount,4542,'$this->r17_codigo','A')");
         $resac = db_query("insert into db_acountkey values($acount,4545,'$this->r17_difere','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r17_anousu"]))
           $resac = db_query("insert into db_acount values($acount,601,4539,'".AddSlashes(pg_result($resaco,$conresaco,'r17_anousu'))."','$this->r17_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r17_mesusu"]))
           $resac = db_query("insert into db_acount values($acount,601,4540,'".AddSlashes(pg_result($resaco,$conresaco,'r17_mesusu'))."','$this->r17_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r17_regist"]))
           $resac = db_query("insert into db_acount values($acount,601,4541,'".AddSlashes(pg_result($resaco,$conresaco,'r17_regist'))."','$this->r17_regist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r17_codigo"]))
           $resac = db_query("insert into db_acount values($acount,601,4542,'".AddSlashes(pg_result($resaco,$conresaco,'r17_codigo'))."','$this->r17_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r17_quant"]))
           $resac = db_query("insert into db_acount values($acount,601,4543,'".AddSlashes(pg_result($resaco,$conresaco,'r17_quant'))."','$this->r17_quant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r17_lotac"]))
           $resac = db_query("insert into db_acount values($acount,601,4544,'".AddSlashes(pg_result($resaco,$conresaco,'r17_lotac'))."','$this->r17_lotac',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r17_difere"]))
           $resac = db_query("insert into db_acount values($acount,601,4545,'".AddSlashes(pg_result($resaco,$conresaco,'r17_difere'))."','$this->r17_difere',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r17_situac"]))
           $resac = db_query("insert into db_acount values($acount,601,4546,'".AddSlashes(pg_result($resaco,$conresaco,'r17_situac'))."','$this->r17_situac',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r17_tipo"]))
           $resac = db_query("insert into db_acount values($acount,601,4547,'".AddSlashes(pg_result($resaco,$conresaco,'r17_tipo'))."','$this->r17_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Funcionarios c/ Vale Transporte nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->r17_anousu."-".$this->r17_mesusu."-".$this->r17_regist."-".$this->r17_codigo."-".$this->r17_difere;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Funcionarios c/ Vale Transporte nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->r17_anousu."-".$this->r17_mesusu."-".$this->r17_regist."-".$this->r17_codigo."-".$this->r17_difere;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r17_anousu."-".$this->r17_mesusu."-".$this->r17_regist."-".$this->r17_codigo."-".$this->r17_difere;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($r17_anousu=null,$r17_mesusu=null,$r17_regist=null,$r17_codigo=null,$r17_difere=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($r17_anousu,$r17_mesusu,$r17_regist,$r17_codigo,$r17_difere));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4539,'$r17_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,4540,'$r17_mesusu','E')");
         $resac = db_query("insert into db_acountkey values($acount,4541,'$r17_regist','E')");
         $resac = db_query("insert into db_acountkey values($acount,4542,'$r17_codigo','E')");
         $resac = db_query("insert into db_acountkey values($acount,4545,'$r17_difere','E')");
         $resac = db_query("insert into db_acount values($acount,601,4539,'','".AddSlashes(pg_result($resaco,$iresaco,'r17_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,601,4540,'','".AddSlashes(pg_result($resaco,$iresaco,'r17_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,601,4541,'','".AddSlashes(pg_result($resaco,$iresaco,'r17_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,601,4542,'','".AddSlashes(pg_result($resaco,$iresaco,'r17_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,601,4543,'','".AddSlashes(pg_result($resaco,$iresaco,'r17_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,601,4544,'','".AddSlashes(pg_result($resaco,$iresaco,'r17_lotac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,601,4545,'','".AddSlashes(pg_result($resaco,$iresaco,'r17_difere'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,601,4546,'','".AddSlashes(pg_result($resaco,$iresaco,'r17_situac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,601,4547,'','".AddSlashes(pg_result($resaco,$iresaco,'r17_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from vtffunc
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($r17_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r17_anousu = $r17_anousu ";
        }
        if($r17_mesusu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r17_mesusu = $r17_mesusu ";
        }
        if($r17_regist != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r17_regist = $r17_regist ";
        }
        if($r17_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r17_codigo = '$r17_codigo' ";
        }
        if($r17_difere != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r17_difere = '$r17_difere' ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Funcionarios c/ Vale Transporte nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$r17_anousu."-".$r17_mesusu."-".$r17_regist."-".$r17_codigo."-".$r17_difere;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Funcionarios c/ Vale Transporte nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$r17_anousu."-".$r17_mesusu."-".$r17_regist."-".$r17_codigo."-".$r17_difere;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$r17_anousu."-".$r17_mesusu."-".$r17_regist."-".$r17_codigo."-".$r17_difere;
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
        $this->erro_sql   = "Record Vazio na Tabela:vtffunc";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $r17_anousu=null,$r17_mesusu=null,$r17_regist=null,$r17_codigo=null,$r17_difere=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from vtffunc ";
     $sql .= "      inner join vtfempr      on  vtfempr.r16_anousu      = vtffunc.r17_anousu 
                                           and  vtfempr.r16_mesusu      = vtffunc.r17_mesusu 
                                           and  vtfempr.r16_codigo      = vtffunc.r17_codigo ";
     $sql .= "      inner join rhempresavt  on  rhempresavt.rh35_codigo = vtfempr.r16_empres::INT 
                                           and  rhempresavt.rh35_instit = ".db_getsession("DB_instit") ;
     $sql2 = "";
     if($dbwhere==""){
       if($r17_anousu!=null ){
         $sql2 .= " where vtffunc.r17_anousu = $r17_anousu "; 
       } 
       if($r17_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " vtffunc.r17_mesusu = $r17_mesusu "; 
       } 
       if($r17_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " vtffunc.r17_regist = $r17_regist "; 
       } 
       if($r17_codigo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " vtffunc.r17_codigo = '$r17_codigo' "; 
       } 
       if($r17_difere!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " vtffunc.r17_difere = '$r17_difere' "; 
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
   function sql_query_file ( $r17_anousu=null,$r17_mesusu=null,$r17_regist=null,$r17_codigo=null,$r17_difere=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from vtffunc ";
     $sql2 = "";
     if($dbwhere==""){
       if($r17_anousu!=null ){
         $sql2 .= " where vtffunc.r17_anousu = $r17_anousu "; 
       } 
       if($r17_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " vtffunc.r17_mesusu = $r17_mesusu "; 
       } 
       if($r17_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " vtffunc.r17_regist = $r17_regist "; 
       } 
       if($r17_codigo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " vtffunc.r17_codigo = '$r17_codigo' "; 
       } 
       if($r17_difere!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " vtffunc.r17_difere = '$r17_difere' "; 
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
   function sql_query_rhpessoal ( $r17_anousu=null,$r17_mesusu=null,$r17_regist=null,$r17_codigo=null,$r17_difere=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from vtffunc ";
     $sql .= "      inner join rhpessoalmov  on rhpessoalmov.rh02_anousu  = vtffunc.r17_anousu
                                            and rhpessoalmov.rh02_mesusu  = vtffunc.r17_mesusu
                                            and rhpessoalmov.rh02_regist  = vtffunc.r17_regist ";
     $sql .= "      left  join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes ";
     $sql .= "      inner join rhpessoal     on rhpessoal.rh01_regist     = rhpessoalmov.rh02_regist ";
     $sql .= "      inner join rhlota        on rhlota.r70_codigo         = rhpessoalmov.rh02_lota ";
     $sql2 = "";
     if($dbwhere==""){
       if($r17_anousu!=null ){
         $sql2 .= " where vtffunc.r17_anousu = $r17_anousu "; 
       } 
       if($r17_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " vtffunc.r17_mesusu = $r17_mesusu "; 
       } 
       if($r17_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " vtffunc.r17_regist = $r17_regist "; 
       } 
       if($r17_codigo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " vtffunc.r17_codigo = '$r17_codigo' "; 
       } 
       if($r17_difere!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " vtffunc.r17_difere = '$r17_difere' "; 
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