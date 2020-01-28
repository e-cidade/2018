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

//MODULO: dbicms
//CLASSE DA ENTIDADE estmun
class cl_estmun { 
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
   var $anousu = 0; 
   var $cgcter = null; 
   var $vlradi = 0; 
   var $popul = 0; 
   var $areaest = 0; 
   var $proprie = 0; 
   var $mortinf = 0; 
   var $evasao = 0; 
   var $projpar = 0; 
   var $propri = 0; 
   var $prodpri = 0; 
   var $reticms = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 anousu = int4 = Exercício 
                 cgcter = char(3) = Codigo do Município 
                 vlradi = float8 = Valor Adicionado 
                 popul = float8 = População 
                 areaest = float8 = Área do Município 
                 proprie = float8 = Propriedades 
                 mortinf = float8 = Mortalidade Infantil 
                 evasao = float8 = Evasão Escolar 
                 projpar = float8 = Projpar 
                 propri = float8 = Propriedades 
                 prodpri = float8 = Prodpri 
                 reticms = float8 = Retorno de ICMS 
                 ";
   //funcao construtor da classe 
   function cl_estmun() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("estmun"); 
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
       $this->anousu = ($this->anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["anousu"]:$this->anousu);
       $this->cgcter = ($this->cgcter == ""?@$GLOBALS["HTTP_POST_VARS"]["cgcter"]:$this->cgcter);
       $this->vlradi = ($this->vlradi == ""?@$GLOBALS["HTTP_POST_VARS"]["vlradi"]:$this->vlradi);
       $this->popul = ($this->popul == ""?@$GLOBALS["HTTP_POST_VARS"]["popul"]:$this->popul);
       $this->areaest = ($this->areaest == ""?@$GLOBALS["HTTP_POST_VARS"]["areaest"]:$this->areaest);
       $this->proprie = ($this->proprie == ""?@$GLOBALS["HTTP_POST_VARS"]["proprie"]:$this->proprie);
       $this->mortinf = ($this->mortinf == ""?@$GLOBALS["HTTP_POST_VARS"]["mortinf"]:$this->mortinf);
       $this->evasao = ($this->evasao == ""?@$GLOBALS["HTTP_POST_VARS"]["evasao"]:$this->evasao);
       $this->projpar = ($this->projpar == ""?@$GLOBALS["HTTP_POST_VARS"]["projpar"]:$this->projpar);
       $this->propri = ($this->propri == ""?@$GLOBALS["HTTP_POST_VARS"]["propri"]:$this->propri);
       $this->prodpri = ($this->prodpri == ""?@$GLOBALS["HTTP_POST_VARS"]["prodpri"]:$this->prodpri);
       $this->reticms = ($this->reticms == ""?@$GLOBALS["HTTP_POST_VARS"]["reticms"]:$this->reticms);
     }else{
       $this->anousu = ($this->anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["anousu"]:$this->anousu);
       $this->cgcter = ($this->cgcter == ""?@$GLOBALS["HTTP_POST_VARS"]["cgcter"]:$this->cgcter);
     }
   }
   // funcao para inclusao
   function incluir ($anousu,$cgcter){ 
      $this->atualizacampos();
     if($this->vlradi == null ){ 
       $this->erro_sql = " Campo Valor Adicionado nao Informado.";
       $this->erro_campo = "vlradi";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->popul == null ){ 
       $this->erro_sql = " Campo População nao Informado.";
       $this->erro_campo = "popul";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->areaest == null ){ 
       $this->erro_sql = " Campo Área do Município nao Informado.";
       $this->erro_campo = "areaest";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->proprie == null ){ 
       $this->erro_sql = " Campo Propriedades nao Informado.";
       $this->erro_campo = "proprie";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->mortinf == null ){ 
       $this->erro_sql = " Campo Mortalidade Infantil nao Informado.";
       $this->erro_campo = "mortinf";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->evasao == null ){ 
       $this->erro_sql = " Campo Evasão Escolar nao Informado.";
       $this->erro_campo = "evasao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->projpar == null ){ 
       $this->erro_sql = " Campo Projpar nao Informado.";
       $this->erro_campo = "projpar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->propri == null ){ 
       $this->erro_sql = " Campo Propriedades nao Informado.";
       $this->erro_campo = "propri";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->prodpri == null ){ 
       $this->erro_sql = " Campo Prodpri nao Informado.";
       $this->erro_campo = "prodpri";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->reticms == null ){ 
       $this->erro_sql = " Campo Retorno de ICMS nao Informado.";
       $this->erro_campo = "reticms";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->anousu = $anousu; 
       $this->cgcter = $cgcter; 
     if(($this->anousu == null) || ($this->anousu == "") ){ 
       $this->erro_sql = " Campo anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->cgcter == null) || ($this->cgcter == "") ){ 
       $this->erro_sql = " Campo cgcter nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into estmun(
                                       anousu 
                                      ,cgcter 
                                      ,vlradi 
                                      ,popul 
                                      ,areaest 
                                      ,proprie 
                                      ,mortinf 
                                      ,evasao 
                                      ,projpar 
                                      ,propri 
                                      ,prodpri 
                                      ,reticms 
                       )
                values (
                                $this->anousu 
                               ,'$this->cgcter' 
                               ,$this->vlradi 
                               ,$this->popul 
                               ,$this->areaest 
                               ,$this->proprie 
                               ,$this->mortinf 
                               ,$this->evasao 
                               ,$this->projpar 
                               ,$this->propri 
                               ,$this->prodpri 
                               ,$this->reticms 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "estmun ($this->anousu."-".$this->cgcter) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "estmun já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "estmun ($this->anousu."-".$this->cgcter) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->anousu."-".$this->cgcter;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->anousu,$this->cgcter));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1019,'$this->anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,2275,'$this->cgcter','I')");
       $resac = db_query("insert into db_acount values($acount,361,1019,'','".AddSlashes(pg_result($resaco,0,'anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,361,2275,'','".AddSlashes(pg_result($resaco,0,'cgcter'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,361,2283,'','".AddSlashes(pg_result($resaco,0,'vlradi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,361,2284,'','".AddSlashes(pg_result($resaco,0,'popul'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,361,2285,'','".AddSlashes(pg_result($resaco,0,'areaest'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,361,2286,'','".AddSlashes(pg_result($resaco,0,'proprie'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,361,2287,'','".AddSlashes(pg_result($resaco,0,'mortinf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,361,2288,'','".AddSlashes(pg_result($resaco,0,'evasao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,361,2289,'','".AddSlashes(pg_result($resaco,0,'projpar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,361,2290,'','".AddSlashes(pg_result($resaco,0,'propri'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,361,2291,'','".AddSlashes(pg_result($resaco,0,'prodpri'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,361,2292,'','".AddSlashes(pg_result($resaco,0,'reticms'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($anousu=null,$cgcter=null) { 
      $this->atualizacampos();
     $sql = " update estmun set ";
     $virgula = "";
     if(trim($this->anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["anousu"])){ 
       $sql  .= $virgula." anousu = $this->anousu ";
       $virgula = ",";
       if(trim($this->anousu) == null ){ 
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cgcter)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cgcter"])){ 
       $sql  .= $virgula." cgcter = '$this->cgcter' ";
       $virgula = ",";
       if(trim($this->cgcter) == null ){ 
         $this->erro_sql = " Campo Codigo do Município nao Informado.";
         $this->erro_campo = "cgcter";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vlradi)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vlradi"])){ 
       $sql  .= $virgula." vlradi = $this->vlradi ";
       $virgula = ",";
       if(trim($this->vlradi) == null ){ 
         $this->erro_sql = " Campo Valor Adicionado nao Informado.";
         $this->erro_campo = "vlradi";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->popul)!="" || isset($GLOBALS["HTTP_POST_VARS"]["popul"])){ 
       $sql  .= $virgula." popul = $this->popul ";
       $virgula = ",";
       if(trim($this->popul) == null ){ 
         $this->erro_sql = " Campo População nao Informado.";
         $this->erro_campo = "popul";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->areaest)!="" || isset($GLOBALS["HTTP_POST_VARS"]["areaest"])){ 
       $sql  .= $virgula." areaest = $this->areaest ";
       $virgula = ",";
       if(trim($this->areaest) == null ){ 
         $this->erro_sql = " Campo Área do Município nao Informado.";
         $this->erro_campo = "areaest";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->proprie)!="" || isset($GLOBALS["HTTP_POST_VARS"]["proprie"])){ 
       $sql  .= $virgula." proprie = $this->proprie ";
       $virgula = ",";
       if(trim($this->proprie) == null ){ 
         $this->erro_sql = " Campo Propriedades nao Informado.";
         $this->erro_campo = "proprie";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->mortinf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["mortinf"])){ 
       $sql  .= $virgula." mortinf = $this->mortinf ";
       $virgula = ",";
       if(trim($this->mortinf) == null ){ 
         $this->erro_sql = " Campo Mortalidade Infantil nao Informado.";
         $this->erro_campo = "mortinf";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->evasao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["evasao"])){ 
       $sql  .= $virgula." evasao = $this->evasao ";
       $virgula = ",";
       if(trim($this->evasao) == null ){ 
         $this->erro_sql = " Campo Evasão Escolar nao Informado.";
         $this->erro_campo = "evasao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->projpar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["projpar"])){ 
       $sql  .= $virgula." projpar = $this->projpar ";
       $virgula = ",";
       if(trim($this->projpar) == null ){ 
         $this->erro_sql = " Campo Projpar nao Informado.";
         $this->erro_campo = "projpar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->propri)!="" || isset($GLOBALS["HTTP_POST_VARS"]["propri"])){ 
       $sql  .= $virgula." propri = $this->propri ";
       $virgula = ",";
       if(trim($this->propri) == null ){ 
         $this->erro_sql = " Campo Propriedades nao Informado.";
         $this->erro_campo = "propri";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->prodpri)!="" || isset($GLOBALS["HTTP_POST_VARS"]["prodpri"])){ 
       $sql  .= $virgula." prodpri = $this->prodpri ";
       $virgula = ",";
       if(trim($this->prodpri) == null ){ 
         $this->erro_sql = " Campo Prodpri nao Informado.";
         $this->erro_campo = "prodpri";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->reticms)!="" || isset($GLOBALS["HTTP_POST_VARS"]["reticms"])){ 
       $sql  .= $virgula." reticms = $this->reticms ";
       $virgula = ",";
       if(trim($this->reticms) == null ){ 
         $this->erro_sql = " Campo Retorno de ICMS nao Informado.";
         $this->erro_campo = "reticms";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($anousu!=null){
       $sql .= " anousu = $this->anousu";
     }
     if($cgcter!=null){
       $sql .= " and  cgcter = '$this->cgcter'";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->anousu,$this->cgcter));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1019,'$this->anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,2275,'$this->cgcter','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["anousu"]))
           $resac = db_query("insert into db_acount values($acount,361,1019,'".AddSlashes(pg_result($resaco,$conresaco,'anousu'))."','$this->anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cgcter"]))
           $resac = db_query("insert into db_acount values($acount,361,2275,'".AddSlashes(pg_result($resaco,$conresaco,'cgcter'))."','$this->cgcter',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vlradi"]))
           $resac = db_query("insert into db_acount values($acount,361,2283,'".AddSlashes(pg_result($resaco,$conresaco,'vlradi'))."','$this->vlradi',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["popul"]))
           $resac = db_query("insert into db_acount values($acount,361,2284,'".AddSlashes(pg_result($resaco,$conresaco,'popul'))."','$this->popul',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["areaest"]))
           $resac = db_query("insert into db_acount values($acount,361,2285,'".AddSlashes(pg_result($resaco,$conresaco,'areaest'))."','$this->areaest',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["proprie"]))
           $resac = db_query("insert into db_acount values($acount,361,2286,'".AddSlashes(pg_result($resaco,$conresaco,'proprie'))."','$this->proprie',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["mortinf"]))
           $resac = db_query("insert into db_acount values($acount,361,2287,'".AddSlashes(pg_result($resaco,$conresaco,'mortinf'))."','$this->mortinf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["evasao"]))
           $resac = db_query("insert into db_acount values($acount,361,2288,'".AddSlashes(pg_result($resaco,$conresaco,'evasao'))."','$this->evasao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["projpar"]))
           $resac = db_query("insert into db_acount values($acount,361,2289,'".AddSlashes(pg_result($resaco,$conresaco,'projpar'))."','$this->projpar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["propri"]))
           $resac = db_query("insert into db_acount values($acount,361,2290,'".AddSlashes(pg_result($resaco,$conresaco,'propri'))."','$this->propri',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["prodpri"]))
           $resac = db_query("insert into db_acount values($acount,361,2291,'".AddSlashes(pg_result($resaco,$conresaco,'prodpri'))."','$this->prodpri',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["reticms"]))
           $resac = db_query("insert into db_acount values($acount,361,2292,'".AddSlashes(pg_result($resaco,$conresaco,'reticms'))."','$this->reticms',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "estmun nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->anousu."-".$this->cgcter;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "estmun nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->anousu."-".$this->cgcter;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->anousu."-".$this->cgcter;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($anousu=null,$cgcter=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($anousu,$cgcter));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1019,'$anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,2275,'$cgcter','E')");
         $resac = db_query("insert into db_acount values($acount,361,1019,'','".AddSlashes(pg_result($resaco,$iresaco,'anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,361,2275,'','".AddSlashes(pg_result($resaco,$iresaco,'cgcter'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,361,2283,'','".AddSlashes(pg_result($resaco,$iresaco,'vlradi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,361,2284,'','".AddSlashes(pg_result($resaco,$iresaco,'popul'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,361,2285,'','".AddSlashes(pg_result($resaco,$iresaco,'areaest'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,361,2286,'','".AddSlashes(pg_result($resaco,$iresaco,'proprie'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,361,2287,'','".AddSlashes(pg_result($resaco,$iresaco,'mortinf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,361,2288,'','".AddSlashes(pg_result($resaco,$iresaco,'evasao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,361,2289,'','".AddSlashes(pg_result($resaco,$iresaco,'projpar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,361,2290,'','".AddSlashes(pg_result($resaco,$iresaco,'propri'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,361,2291,'','".AddSlashes(pg_result($resaco,$iresaco,'prodpri'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,361,2292,'','".AddSlashes(pg_result($resaco,$iresaco,'reticms'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from estmun
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " anousu = $anousu ";
        }
        if($cgcter != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " cgcter = '$cgcter' ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "estmun nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$anousu."-".$cgcter;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "estmun nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$anousu."-".$cgcter;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$anousu."-".$cgcter;
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
        $this->erro_sql   = "Record Vazio na Tabela:estmun";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>