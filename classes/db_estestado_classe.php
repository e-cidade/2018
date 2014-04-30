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
//CLASSE DA ENTIDADE estestado
class cl_estestado { 
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
   var $vlradi = 0; 
   var $vlradiperc = 0; 
   var $popul = 0; 
   var $populperc = 0; 
   var $areaest = 0; 
   var $areaestperc = 0; 
   var $proprie = 0; 
   var $proprieperc = 0; 
   var $mortinf = 0; 
   var $mortinfperc = 0; 
   var $evasao = 0; 
   var $evasaoperc = 0; 
   var $projpar = 0; 
   var $projparperc = 0; 
   var $propri = 0; 
   var $propriperc = 0; 
   var $prodpri = 0; 
   var $prodpriperc = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 anousu = int4 = Exercício 
                 vlradi = float8 = Valor Adicionado 
                 vlradiperc = float8 = % 
                 popul = float8 = População 
                 populperc = float8 = % 
                 areaest = float8 = Área do Município 
                 areaestperc = float8 = % 
                 proprie = float8 = Propriedades 
                 proprieperc = float8 = % 
                 mortinf = float8 = Mortalidade Infantil 
                 mortinfperc = float8 = % 
                 evasao = float8 = Evasão Escolar 
                 evasaoperc = float8 = % 
                 projpar = float8 = Projpar 
                 projparperc = float8 = % 
                 propri = float8 = Propriedades 
                 propriperc = float8 = % 
                 prodpri = float8 = Prodpri 
                 prodpriperc = float8 = % 
                 ";
   //funcao construtor da classe 
   function cl_estestado() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("estestado"); 
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
       $this->vlradi = ($this->vlradi == ""?@$GLOBALS["HTTP_POST_VARS"]["vlradi"]:$this->vlradi);
       $this->vlradiperc = ($this->vlradiperc == ""?@$GLOBALS["HTTP_POST_VARS"]["vlradiperc"]:$this->vlradiperc);
       $this->popul = ($this->popul == ""?@$GLOBALS["HTTP_POST_VARS"]["popul"]:$this->popul);
       $this->populperc = ($this->populperc == ""?@$GLOBALS["HTTP_POST_VARS"]["populperc"]:$this->populperc);
       $this->areaest = ($this->areaest == ""?@$GLOBALS["HTTP_POST_VARS"]["areaest"]:$this->areaest);
       $this->areaestperc = ($this->areaestperc == ""?@$GLOBALS["HTTP_POST_VARS"]["areaestperc"]:$this->areaestperc);
       $this->proprie = ($this->proprie == ""?@$GLOBALS["HTTP_POST_VARS"]["proprie"]:$this->proprie);
       $this->proprieperc = ($this->proprieperc == ""?@$GLOBALS["HTTP_POST_VARS"]["proprieperc"]:$this->proprieperc);
       $this->mortinf = ($this->mortinf == ""?@$GLOBALS["HTTP_POST_VARS"]["mortinf"]:$this->mortinf);
       $this->mortinfperc = ($this->mortinfperc == ""?@$GLOBALS["HTTP_POST_VARS"]["mortinfperc"]:$this->mortinfperc);
       $this->evasao = ($this->evasao == ""?@$GLOBALS["HTTP_POST_VARS"]["evasao"]:$this->evasao);
       $this->evasaoperc = ($this->evasaoperc == ""?@$GLOBALS["HTTP_POST_VARS"]["evasaoperc"]:$this->evasaoperc);
       $this->projpar = ($this->projpar == ""?@$GLOBALS["HTTP_POST_VARS"]["projpar"]:$this->projpar);
       $this->projparperc = ($this->projparperc == ""?@$GLOBALS["HTTP_POST_VARS"]["projparperc"]:$this->projparperc);
       $this->propri = ($this->propri == ""?@$GLOBALS["HTTP_POST_VARS"]["propri"]:$this->propri);
       $this->propriperc = ($this->propriperc == ""?@$GLOBALS["HTTP_POST_VARS"]["propriperc"]:$this->propriperc);
       $this->prodpri = ($this->prodpri == ""?@$GLOBALS["HTTP_POST_VARS"]["prodpri"]:$this->prodpri);
       $this->prodpriperc = ($this->prodpriperc == ""?@$GLOBALS["HTTP_POST_VARS"]["prodpriperc"]:$this->prodpriperc);
     }else{
       $this->anousu = ($this->anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["anousu"]:$this->anousu);
     }
   }
   // funcao para inclusao
   function incluir ($anousu){ 
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
     if($this->vlradiperc == null ){ 
       $this->erro_sql = " Campo % nao Informado.";
       $this->erro_campo = "vlradiperc";
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
     if($this->populperc == null ){ 
       $this->erro_sql = " Campo % nao Informado.";
       $this->erro_campo = "populperc";
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
     if($this->areaestperc == null ){ 
       $this->erro_sql = " Campo % nao Informado.";
       $this->erro_campo = "areaestperc";
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
     if($this->proprieperc == null ){ 
       $this->erro_sql = " Campo % nao Informado.";
       $this->erro_campo = "proprieperc";
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
     if($this->mortinfperc == null ){ 
       $this->erro_sql = " Campo % nao Informado.";
       $this->erro_campo = "mortinfperc";
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
     if($this->evasaoperc == null ){ 
       $this->erro_sql = " Campo % nao Informado.";
       $this->erro_campo = "evasaoperc";
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
     if($this->projparperc == null ){ 
       $this->erro_sql = " Campo % nao Informado.";
       $this->erro_campo = "projparperc";
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
     if($this->propriperc == null ){ 
       $this->erro_sql = " Campo % nao Informado.";
       $this->erro_campo = "propriperc";
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
     if($this->prodpriperc == null ){ 
       $this->erro_sql = " Campo % nao Informado.";
       $this->erro_campo = "prodpriperc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->anousu = $anousu; 
     if(($this->anousu == null) || ($this->anousu == "") ){ 
       $this->erro_sql = " Campo anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into estestado(
                                       anousu 
                                      ,vlradi 
                                      ,vlradiperc 
                                      ,popul 
                                      ,populperc 
                                      ,areaest 
                                      ,areaestperc 
                                      ,proprie 
                                      ,proprieperc 
                                      ,mortinf 
                                      ,mortinfperc 
                                      ,evasao 
                                      ,evasaoperc 
                                      ,projpar 
                                      ,projparperc 
                                      ,propri 
                                      ,propriperc 
                                      ,prodpri 
                                      ,prodpriperc 
                       )
                values (
                                $this->anousu 
                               ,$this->vlradi 
                               ,$this->vlradiperc 
                               ,$this->popul 
                               ,$this->populperc 
                               ,$this->areaest 
                               ,$this->areaestperc 
                               ,$this->proprie 
                               ,$this->proprieperc 
                               ,$this->mortinf 
                               ,$this->mortinfperc 
                               ,$this->evasao 
                               ,$this->evasaoperc 
                               ,$this->projpar 
                               ,$this->projparperc 
                               ,$this->propri 
                               ,$this->propriperc 
                               ,$this->prodpri 
                               ,$this->prodpriperc 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Dados do Estado ($this->anousu) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Dados do Estado já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Dados do Estado ($this->anousu) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->anousu;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->anousu));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1019,'$this->anousu','I')");
       $resac = db_query("insert into db_acount values($acount,382,1019,'','".AddSlashes(pg_result($resaco,0,'anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,382,2283,'','".AddSlashes(pg_result($resaco,0,'vlradi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,382,2348,'','".AddSlashes(pg_result($resaco,0,'vlradiperc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,382,2284,'','".AddSlashes(pg_result($resaco,0,'popul'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,382,2349,'','".AddSlashes(pg_result($resaco,0,'populperc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,382,2285,'','".AddSlashes(pg_result($resaco,0,'areaest'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,382,2350,'','".AddSlashes(pg_result($resaco,0,'areaestperc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,382,2286,'','".AddSlashes(pg_result($resaco,0,'proprie'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,382,2351,'','".AddSlashes(pg_result($resaco,0,'proprieperc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,382,2287,'','".AddSlashes(pg_result($resaco,0,'mortinf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,382,2353,'','".AddSlashes(pg_result($resaco,0,'mortinfperc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,382,2288,'','".AddSlashes(pg_result($resaco,0,'evasao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,382,2354,'','".AddSlashes(pg_result($resaco,0,'evasaoperc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,382,2289,'','".AddSlashes(pg_result($resaco,0,'projpar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,382,2355,'','".AddSlashes(pg_result($resaco,0,'projparperc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,382,2290,'','".AddSlashes(pg_result($resaco,0,'propri'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,382,2356,'','".AddSlashes(pg_result($resaco,0,'propriperc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,382,2291,'','".AddSlashes(pg_result($resaco,0,'prodpri'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,382,2357,'','".AddSlashes(pg_result($resaco,0,'prodpriperc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($anousu=null) { 
      $this->atualizacampos();
     $sql = " update estestado set ";
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
     if(trim($this->vlradiperc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vlradiperc"])){ 
       $sql  .= $virgula." vlradiperc = $this->vlradiperc ";
       $virgula = ",";
       if(trim($this->vlradiperc) == null ){ 
         $this->erro_sql = " Campo % nao Informado.";
         $this->erro_campo = "vlradiperc";
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
     if(trim($this->populperc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["populperc"])){ 
       $sql  .= $virgula." populperc = $this->populperc ";
       $virgula = ",";
       if(trim($this->populperc) == null ){ 
         $this->erro_sql = " Campo % nao Informado.";
         $this->erro_campo = "populperc";
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
     if(trim($this->areaestperc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["areaestperc"])){ 
       $sql  .= $virgula." areaestperc = $this->areaestperc ";
       $virgula = ",";
       if(trim($this->areaestperc) == null ){ 
         $this->erro_sql = " Campo % nao Informado.";
         $this->erro_campo = "areaestperc";
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
     if(trim($this->proprieperc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["proprieperc"])){ 
       $sql  .= $virgula." proprieperc = $this->proprieperc ";
       $virgula = ",";
       if(trim($this->proprieperc) == null ){ 
         $this->erro_sql = " Campo % nao Informado.";
         $this->erro_campo = "proprieperc";
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
     if(trim($this->mortinfperc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["mortinfperc"])){ 
       $sql  .= $virgula." mortinfperc = $this->mortinfperc ";
       $virgula = ",";
       if(trim($this->mortinfperc) == null ){ 
         $this->erro_sql = " Campo % nao Informado.";
         $this->erro_campo = "mortinfperc";
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
     if(trim($this->evasaoperc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["evasaoperc"])){ 
       $sql  .= $virgula." evasaoperc = $this->evasaoperc ";
       $virgula = ",";
       if(trim($this->evasaoperc) == null ){ 
         $this->erro_sql = " Campo % nao Informado.";
         $this->erro_campo = "evasaoperc";
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
     if(trim($this->projparperc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["projparperc"])){ 
       $sql  .= $virgula." projparperc = $this->projparperc ";
       $virgula = ",";
       if(trim($this->projparperc) == null ){ 
         $this->erro_sql = " Campo % nao Informado.";
         $this->erro_campo = "projparperc";
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
     if(trim($this->propriperc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["propriperc"])){ 
       $sql  .= $virgula." propriperc = $this->propriperc ";
       $virgula = ",";
       if(trim($this->propriperc) == null ){ 
         $this->erro_sql = " Campo % nao Informado.";
         $this->erro_campo = "propriperc";
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
     if(trim($this->prodpriperc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["prodpriperc"])){ 
       $sql  .= $virgula." prodpriperc = $this->prodpriperc ";
       $virgula = ",";
       if(trim($this->prodpriperc) == null ){ 
         $this->erro_sql = " Campo % nao Informado.";
         $this->erro_campo = "prodpriperc";
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
     $resaco = $this->sql_record($this->sql_query_file($this->anousu));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1019,'$this->anousu','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["anousu"]))
           $resac = db_query("insert into db_acount values($acount,382,1019,'".AddSlashes(pg_result($resaco,$conresaco,'anousu'))."','$this->anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vlradi"]))
           $resac = db_query("insert into db_acount values($acount,382,2283,'".AddSlashes(pg_result($resaco,$conresaco,'vlradi'))."','$this->vlradi',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vlradiperc"]))
           $resac = db_query("insert into db_acount values($acount,382,2348,'".AddSlashes(pg_result($resaco,$conresaco,'vlradiperc'))."','$this->vlradiperc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["popul"]))
           $resac = db_query("insert into db_acount values($acount,382,2284,'".AddSlashes(pg_result($resaco,$conresaco,'popul'))."','$this->popul',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["populperc"]))
           $resac = db_query("insert into db_acount values($acount,382,2349,'".AddSlashes(pg_result($resaco,$conresaco,'populperc'))."','$this->populperc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["areaest"]))
           $resac = db_query("insert into db_acount values($acount,382,2285,'".AddSlashes(pg_result($resaco,$conresaco,'areaest'))."','$this->areaest',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["areaestperc"]))
           $resac = db_query("insert into db_acount values($acount,382,2350,'".AddSlashes(pg_result($resaco,$conresaco,'areaestperc'))."','$this->areaestperc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["proprie"]))
           $resac = db_query("insert into db_acount values($acount,382,2286,'".AddSlashes(pg_result($resaco,$conresaco,'proprie'))."','$this->proprie',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["proprieperc"]))
           $resac = db_query("insert into db_acount values($acount,382,2351,'".AddSlashes(pg_result($resaco,$conresaco,'proprieperc'))."','$this->proprieperc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["mortinf"]))
           $resac = db_query("insert into db_acount values($acount,382,2287,'".AddSlashes(pg_result($resaco,$conresaco,'mortinf'))."','$this->mortinf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["mortinfperc"]))
           $resac = db_query("insert into db_acount values($acount,382,2353,'".AddSlashes(pg_result($resaco,$conresaco,'mortinfperc'))."','$this->mortinfperc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["evasao"]))
           $resac = db_query("insert into db_acount values($acount,382,2288,'".AddSlashes(pg_result($resaco,$conresaco,'evasao'))."','$this->evasao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["evasaoperc"]))
           $resac = db_query("insert into db_acount values($acount,382,2354,'".AddSlashes(pg_result($resaco,$conresaco,'evasaoperc'))."','$this->evasaoperc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["projpar"]))
           $resac = db_query("insert into db_acount values($acount,382,2289,'".AddSlashes(pg_result($resaco,$conresaco,'projpar'))."','$this->projpar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["projparperc"]))
           $resac = db_query("insert into db_acount values($acount,382,2355,'".AddSlashes(pg_result($resaco,$conresaco,'projparperc'))."','$this->projparperc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["propri"]))
           $resac = db_query("insert into db_acount values($acount,382,2290,'".AddSlashes(pg_result($resaco,$conresaco,'propri'))."','$this->propri',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["propriperc"]))
           $resac = db_query("insert into db_acount values($acount,382,2356,'".AddSlashes(pg_result($resaco,$conresaco,'propriperc'))."','$this->propriperc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["prodpri"]))
           $resac = db_query("insert into db_acount values($acount,382,2291,'".AddSlashes(pg_result($resaco,$conresaco,'prodpri'))."','$this->prodpri',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["prodpriperc"]))
           $resac = db_query("insert into db_acount values($acount,382,2357,'".AddSlashes(pg_result($resaco,$conresaco,'prodpriperc'))."','$this->prodpriperc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Dados do Estado nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->anousu;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Dados do Estado nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->anousu;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->anousu;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($anousu=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($anousu));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1019,'$anousu','E')");
         $resac = db_query("insert into db_acount values($acount,382,1019,'','".AddSlashes(pg_result($resaco,$iresaco,'anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,382,2283,'','".AddSlashes(pg_result($resaco,$iresaco,'vlradi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,382,2348,'','".AddSlashes(pg_result($resaco,$iresaco,'vlradiperc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,382,2284,'','".AddSlashes(pg_result($resaco,$iresaco,'popul'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,382,2349,'','".AddSlashes(pg_result($resaco,$iresaco,'populperc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,382,2285,'','".AddSlashes(pg_result($resaco,$iresaco,'areaest'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,382,2350,'','".AddSlashes(pg_result($resaco,$iresaco,'areaestperc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,382,2286,'','".AddSlashes(pg_result($resaco,$iresaco,'proprie'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,382,2351,'','".AddSlashes(pg_result($resaco,$iresaco,'proprieperc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,382,2287,'','".AddSlashes(pg_result($resaco,$iresaco,'mortinf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,382,2353,'','".AddSlashes(pg_result($resaco,$iresaco,'mortinfperc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,382,2288,'','".AddSlashes(pg_result($resaco,$iresaco,'evasao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,382,2354,'','".AddSlashes(pg_result($resaco,$iresaco,'evasaoperc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,382,2289,'','".AddSlashes(pg_result($resaco,$iresaco,'projpar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,382,2355,'','".AddSlashes(pg_result($resaco,$iresaco,'projparperc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,382,2290,'','".AddSlashes(pg_result($resaco,$iresaco,'propri'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,382,2356,'','".AddSlashes(pg_result($resaco,$iresaco,'propriperc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,382,2291,'','".AddSlashes(pg_result($resaco,$iresaco,'prodpri'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,382,2357,'','".AddSlashes(pg_result($resaco,$iresaco,'prodpriperc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from estestado
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " anousu = $anousu ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Dados do Estado nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$anousu;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Dados do Estado nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$anousu;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$anousu;
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
        $this->erro_sql   = "Record Vazio na Tabela:estestado";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>